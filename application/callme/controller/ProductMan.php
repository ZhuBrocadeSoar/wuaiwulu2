<?php
namespace app\callme\controller;

use app\callme\controller\WxApi;
use app\callme\controller\Auth;
use think\Request;
use app\callme\model\Session;
use app\callme\model\Seller;
use app\callme\model\Picture;
use app\callme\model\Order;
use app\callme\model\Product;
use app\callme\model\ProductHistory;
use app\callme\model\User;
use app\callme\model\DiningHall;

class ProductMan extends \think\Controller{
    public function updateSellerPicture(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 商家、总管理员、开发者可调用
            if(!Auth::checkPass($session->openid, ['seller', 'platform', 'develop']))
                return Auth::permissionDenided();
            $openid = $session->openid;
            $seller = Seller::get(["openid" => $openid]);
            if(is_uploaded_file($_FILES['picture']['tmp_name'])){
                // 上传了图片
                if(filesize($_FILES['picture']['tmp_name']) > 500000){
                    // 图片太大
                    return json_encode([
                        "errmsg" => "Too Large",
                    ]);
                }else{
                    // 图片合适
                    $picture = Picture::get($seller->pic_id);
                    if($picture == NULL){
                        $picture = new Picture;
                        $picture->image_str = 'void';
                        $picture->isUpdate(false)->save();
                        $seller->pic_id = $picture->id;
                        $seller->isUpdate(true)->save();
                    }else{
                        // 已有图片
                    }
                    $picture->image_str = file_get_contents($_FILES['picture']['tmp_name']);
                    $picture->isUpdate(true)->save();
                    return json_encode([
                        "errmsg" => "ok: Picture Updated",
                    ]);
                }
            }else{
                // 没有图片上传
                return json_encode([
                    "errmsg" => "No Picture Uploaded",
                ]);
            }
        }
    }

    public function updateSellerName(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 商家、总管理员、开发者可调用
            if(!Auth::checkPass($session->openid, ['seller', 'platform', 'develop']))
                return Auth::permissionDenided();
            $openid = $session->openid;
            $seller = Seller::get(["openid" => $openid]);
            $seller->name = Request::instance()->param('name');
            $seller->isUpdate(true)->save();
            return json_encode([
                "errmsg" => "ok: Name Updated",
            ]);
        }
    }

    public function remove(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 商家、总管理员、开发者可调用
            if(!Auth::checkPass($session->openid, ['seller', 'platform', 'develop']))
                return Auth::permissionDenided();
            $openid = $session->openid;
            $seller = Seller::get(["openid" => $openid]);
            $productList = $seller->product_list;
            foreach($productList as $key => $val){
                if($val == Request::instance()->param('product_id')) unset($productList[$key]);
            }
            $productNewList = array_values($productList);
            $seller->product_list = implode(',', $productNewList);
            $seller->isUpdate(true)->save();
            return json_encode([
                "errmsg" => "ok: Product Removed",
            ]);
        }
    }

    public function update(){
        $session = Session::get([
            'session_id' => Request::instance()->param('session_id'),
        ]);
        if($session == NULL){
            return json_encode([
                "errmsg" => "Session Time Out",
            ]);
        }else{
            // 商家、总管理员、开发者可调用
            if(!Auth::checkPass($session->openid, ['seller', 'platform', 'develop']))
                return Auth::permissionDenided();
            $newName = Request::instance()->param('product_name');
            $newTotalFee = Request::instance()->param('product_total_fee');
            $newInventory = Request::instance()->param('product_inventory');
            $newLabel = Request::instance()->param('product_label');
            $picSaveError = "OK";
            if(is_uploaded_file($_FILES['picture']['tmp_name'])){
                // 图片上传
                if(filesize($_FILES['picture']['tmp_name']) > 500000){
                    // 图片太大
                    $picSaveError = "Too Large";
                    $newPicId = NULL;
                }else{
                    // 图片合格，保存并分配PicId
                    /*
                    $newPic = file_get_contents($_FILES['picture']['tmp_name']);
                    $product = Product::get(Request::instance()->has('product_id'));
                    $oldPic = Picture::get($product->pic_id);
                     */
                    if((Request::instance()->param('is_picture_changed') == 'true')?false:true){
                        // 图片没有改变
                        $isNewPicEqOld = true;
                        $newPicId = NULL;
                    }else{
                        $isNewPicEqOld = false;
                        $pic = new Picture;
                        $pic->image_str = file_get_contents($_FILES['picture']['tmp_name']);
                        $pic->isUpdate(false)->save();
                        $newPicId = $pic->id;
                    }
                }
            }else{
                // 没有图片上传
                $newPicId = NULL;
                $picSaveError = "No Picture Uploaded";
            }
            if(Request::instance()->has('product_id')){
                // 非新增更新
                // 名字、价格、标签、图片任意一项更新时需要迭代历史记录
                $product = Product::get(Request::instance()->param('product_id'));
                $isNameDiff = $newName != $product->name;
                $isTotalFeeDiff = $newTotalFee != $product->total_fee;
                $isLabelDiff = $newLabel != $product->label;
                if($newPicId == NULL) $isPicIdDiff = false;
                else $isPicIdDiff = !$isNewPicEqOld;
                if($isNameDiff || $isTotalFeeDiff || $isLabelDiff || $isPicIdDiff){
                    // 任意一项不同需要迭代历史记录
                    $productHistory = new ProductHistory;
                    $productHistory->data([
                        "name" => base64_encode($product->name),
                        "total_fee" => $product->total_fee,
                        "inventory" => $product->inventory,
                        "label" => base64_encode($product->label),
                        "seller_id" => $product->seller_id,
                        "pic_id" => $product->pic_id,
                    ]);
                    $productHistory->isUpdate(false)->save();

                    $product->data([
                        "name" => base64_encode($newName),
                        "total_fee" => $newTotalFee,
                        "inventory" => $newInventory,
                        "label" => base64_encode($newLabel),
                        "history_id" => $productHistory->id,
                    ]);
                    $product->isUpdate(true)->save();

                    if($newPicId == NULL){
                        return json_encode([
                            "errmsg" => "ok: Update Done",
                            "picture_error" => $picSaveError,
                        ]);
                    }else{
                        $product->pic_id = $newPicId;
                        $product->isUpdate(true)->save();
                        return json_encode([
                            "errmsg" => "ok: Update Done",
                        ]);
                    }
                }else{
                    // 不需要迭代历史记录(只有inventory改变)
                    $product->inventory = $newInventory;
                    $product->isUpdate(true)->save();
                    return json_encode([
                        "errmsg" => "ok: Inventory Updated",
                    ]);
                }
            }else{
                // 新增更新
                $openid = $session->openid;
                $seller = Seller::get(["openid" => $openid]);

                $productHistory = new ProductHistory;
                $productHistory->data([
                    "name" => base64_encode($newName),
                    "total_fee" => $newTotalFee,
                    "inventory" => $newInventory,
                    "label" => base64_encode($newLabel),
                    "seller_id" => $seller->id,
                ]);
                $productHistory->isUpdate(false)->save();

                $product = new Product;
                $product->data([
                    "name" => base64_encode($newName),
                    "total_fee" => $newTotalFee,
                    "inventory" => $newInventory,
                    "label" => base64_encode($newLabel),
                    "history_id" => $productHistory->id,
                    "seller_id" => $seller->id,
                ]);
                $product->isUpdate(false)->save();

                $sellerOldProductList = $seller->product_list;
                if($sellerOldProductList[0] == 0){
                    unset($sellerOldProductList[0]);
                    $sellerOldProductList = array_values($sellerOldProductList);
                }
                $sellerOldProductList[] = $product->id;
                $seller->product_list = implode(',', $sellerOldProductList);
                $seller->isUpdate(true)->save();

                if($newPicId == NULL){
                    return json_encode([
                        "errmsg" => "ok: Create Done",
                        "picture_error" => $picSaveError,
                    ]);
                }else{
                    $product->pic_id = $newPicId;
                    $product->isUpdate(true)->save();
                    return json_encode([
                        "errmsg" => "ok: Create Done",
                    ]);
                }
            }
        }
    }
}

?>
