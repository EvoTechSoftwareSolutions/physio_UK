<?php
session_start();
require "connection.php";
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Colombo');

$imgQ = "SELECT product_id, img FROM (SELECT id, product_id, img,
ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY id) AS rn
FROM product_images WHERE `img_status` = '1') AS subset
WHERE rn = '1'";

$date = date("Y-m-d h:i:s");

function getCategory()
{
    $cat_rs = Database::search("SELECT * FROM `category`;");
    $cat = [];

    for ($i = 0; $cat_rs->num_rows > $i; $i++) {
        $cat[] = $cat_rs->fetch_assoc();
    }
    return $cat;
}

function getRecProducts($limit, $offset)
{
    $products = [];
    global $imgQ;

    $params = "";

    if (isset($limit) && !empty($limit) && $limit != "0") {
        $params .= " LIMIT " . $limit . "";
    }

    if (isset($offset) && !empty($offset) && $offset != "0") {
        $params .= " OFFSET " . $offset . "";
    }

    if (!isset($_SESSION["u"]["id"])) {
        $products_rs = Database::search("SELECT p.* , img
        FROM product AS p
        INNER JOIN (" . $imgQ . ") AS pro_i 
        ON p.id = pro_i.product_id
        INNER JOIN `category` ON p.category_id = `category`.`id`
        ORDER BY `date`" . $params . "");
        echo "no user";
        if ($products_rs->num_rows > 0) {
            for ($i = 0; $i < $products_rs->num_rows; $i++) {
                $products[] = $products_rs->fetch_assoc();
            }
            return $products;
        } else {
            return null;
        }
    } else {


        $q = "SELECT p.* , img, category
        FROM product AS p
        INNER JOIN (" . $imgQ . ") AS pro_i 
        ON p.id = pro_i.product_id 
        INNER JOIN `category` ON p.category_id = `category`.`id`
        WHERE p.category_id IN (
            SELECT DISTINCT category_id w
            FROM orders AS o 
            INNER JOIN order_item AS oi ON o.id = oi.order_id 
            INNER JOIN product AS p ON p.id = oi.product_id 
            WHERE customer_id = '" . $_SESSION["u"]["id"] . "'
        )" . $params . ";";

        $product_rs = Database::search($q);

        if ($product_rs->num_rows > 0) {
            for ($i = 0; $i < $product_rs->num_rows; $i++) {
                $products[] = $product_rs->fetch_assoc();
            }
        } else {
            $products = NULL;
        }

        return $products;
    }
}

function searchProducts($st, $cat, $minP, $maxP, $limit, $offset, $promotions)
{

    global $imgQ;

    $products = [];

    $params = "";

    $cons = "";

    if (isset($limit) && !empty($limit) && $limit != "0") {
        $params .= " LIMIT " . $limit . "";
    }

    if (isset($offset) && !empty($offset) && $offset != "0") {
        $params .= " OFFSET " . $offset . "";
    }

    if (empty($st) || !is_string($st)) {
        $st = "";
    }

    if (!empty($cat) && $cat != "0") {
        $cons .= " AND `category_id` = '" . $cat . "'";
    }

    if (!empty($minP) && !empty($maxP)) {
        $cons .= " AND `price` BETWEEN '" . $minP . "' AND '" . $maxP . "'";
    } elseif (!empty($minP)) {
        $cons .= " AND `price` >= '" . $minP . "'";
    } elseif (!empty($maxP)) {
        $cons .= " AND `price` <= '" . $maxP . "'";
    }

    $promoQ = "LEFT JOIN `promo_item` ON `p`.`id` = `promo_item`.`product_id`
    LEFT JOIN `promo` ON `promo`.`promo_id` = `promo_item`.`promo_id`";

    if ($promotions == 1) {
        $promoQ = "INNER JOIN `promo_item` ON `p`.`id` = `promo_item`.`product_id`
        INNER JOIN `promo` ON `promo`.`promo_id` = `promo_item`.`promo_id`";
        $cons .= " ORDER BY `promo`.`date` DESC ";
    }

    $q = "SELECT p.* , img, category, promo_item.promo_id, new_price
    FROM product AS p
    LEFT JOIN (" . $imgQ . ") AS pro_i 
    ON p.id = pro_i.product_id 
    " . $promoQ . "
    INNER JOIN `category` ON p.category_id = `category`.`id`
    WHERE `status` = '1' AND `title` LIKE '%" . $st . "%'" . $cons . $params;

    $product_rs = Database::search($q);
    if ($product_rs->num_rows > 0) {
        for ($i = 0; $i < $product_rs->num_rows; $i++) {
            $products[] = $product_rs->fetch_assoc();
        }
        return [
            "products" => $products,
            "offset" => $offset,
            "limit" => $limit
        ];
    } else {
        return [
            "products" => null,
            "offset" => $offset,
            "limit" => $limit
        ];
    }
}

function calcDisc($oldPrice, $newPrice)
{
    // if (is_double($oldPrice) && is_double($newPrice)) {
    $disc = $oldPrice - $newPrice;
    $discPrecent = $disc / $oldPrice * 100;
    return [
        "discount" => $disc,
        "precentage" => ceil($discPrecent)
    ];
    // } else {
    return null;
    //}
}

function renderCards($data, $paginations)
{
    if (is_array($data)) {
        if ($paginations) {

            $limit = $data["limit"];
            $offset = $data["offset"];

            $page = ($offset / $limit) + 1;

            if ($offset != 0) {
                $prev = $offset - $limit;
            }

            $next = $offset + $limit;

?>
            <div class="paginationRow">
                <div class="productPagination">
                    <?php
                    if ($page > 1) {
                    ?>
                        <button class="pagination--nextBtn" id="btnNext" onclick="search(<?php echo $prev; ?>)">
                            <img src="../products/resources/left.png"></button>
                    <?php
                    } else {
                    ?>
                        <button class="pagination--nextBtn" id="btnNext" disabled>
                            <img src="../products/resources/left_d.png"></button>
                    <?php
                    }
                    ?>

                    <span>Page</span>&nbsp;&nbsp;
                    <span><?php echo $page; ?></span>
                    <?php
                    if ($data["products"]) {
                    ?>
                        <button class="pagination--nextBtn" id="btnPrev" onclick="search(<?php echo $next; ?>)"><img src="../products/resources/right.png"></button>
                    <?php
                    } else {
                    ?>
                        <button class="pagination--nextBtn" id="btnPrev" disabled><img src="../products/resources/right_d.png"></button>
                    <?php
                    }
                    ?>

                </div>
            </div>
            <?php
        }
        if ($data["products"]) {

            foreach ($data["products"] as $product) {
                $promo = false;
                $disc = null;
                if (isset($product["new_price"]) && $product["new_price"] != null) {
                    $promo = true;
                    $disc = calcDisc($product["price"], $product["new_price"]);
                }
            ?>
                <article class="productCard" style="cursor: pointer;">
                    <div class="productCard--img" onclick="window.location.href='../singleProductView/index.php?pid=<?php echo $product['id']; ?>';" style="background-image: url('../_resources/img/products/<?php echo ($product["img"]); ?>');">
                        <?php
                        if ($disc) {
                        ?>
                            <div class="productCard--discBlock">
                                <?php echo $disc["precentage"] . "% OFF"; ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="productCard--content">
                        <span class="productCard--t1" onclick="window.location.href='../singleProductView/index.php?pid=<?php echo $product['id']; ?>';"><?php echo ($product["title"]); ?></span>
                        <span class="productCard--sold" onclick="window.location.href='../singleProductView/index.php?pid=<?php echo $product['id']; ?>';"><?php echo ($product["category"]); ?></span>
                        <div class="productCard--row2" onclick="window.location.href='../singleProductView/index.php?pid=<?php echo $product['id']; ?>';">

                            <?php
                            if ($product["qty"] > 10) {
                            ?>
                                <div class="productCard--qty" style="background-color: #00FF00;">
                                    <span class="pcQty--text">In&nbsp;stock</span>
                                </div>
                            <?php
                            } else if ($product["qty"] > 0 && $product["qty"] <= 10) {
                            ?>
                                <div class="productCard--qty" style="background-color: #f7bc0a  ;">
                                    <span class="pcQty--text">Limited</span>
                                </div>
                            <?php
                            } else if ($product["qty"] == 0) {
                            ?>
                                <div class="productCard--qty" style="background-color: #f7110a;">
                                    <span class="pcQty--text" style="color: #FFFFFF;">Sold&nbsp;out</span>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="productCard--priceCol">
                                <?php
                                if ($promo) {
                                ?>
                                    <span class="productCard--t2">Rs. <?php echo ($product["new_price"]); ?></span>
                                    <span class="productCard--oldPrice">Rs. <?php echo ($product["price"]); ?></span>
                                <?php
                                } else {
                                ?>
                                    <span class="productCard--t2">Rs. <?php echo ($product["price"]); ?></span>
                                <?php
                                }
                                ?>

                            </div>


                        </div>
                        <!-- <a href="../singleProductView/index.php?pid=<?php echo $product["id"]; ?>" class="productCard--btn">Buy now</a> -->

                        <?php
                        if (isset($_SESSION["u"]["id"]) && checkUser($_SESSION["u"]["id"])) {
                        ?>
                            <a onclick="addToCart(<?php echo $product['id']; ?>);" class="productCard--btn" style="cursor: pointer;">Add to Cart</a>
                        <?php
                        } else {
                        ?>
                            <a href="../login/index.php" class="productCard--btn" style="cursor: pointer;">Add to Cart</a>
                        <?php
                        }
                        ?>

                    </div>
                </article>

            <?php
            }
        } else {
            echo $data["products"];
            ?>
            <img src="../_resources/offer_no1.png" class="no_offer" />
        <?php
        }
    } else {
        return "";
    }
}

function getProduct($pid)
{
    global $imgQ;

    $product_rs = Database::search("SELECT p.* , category
    FROM product AS p
    INNER JOIN `category` ON p.category_id = `category`.`id`
    WHERE p.`id` = '" . $pid . "' AND `status` = '1'");
    if ($product_rs->num_rows > 0) {
        $product = $product_rs->fetch_assoc();

        $variations = [];

        $variation_rs = Database::search("SELECT * FROM `variation` WHERE `product_id` = '" . $pid . "'");
        if ($variation_rs->num_rows > 0) {
            for ($i = 0; $i < $variation_rs->num_rows; $i++) {
                $variations[] = $variation_rs->fetch_assoc();
            }
            $product["variations"] = $variations;
        } else {
            $product["variations"] = null;
        }

        $promos_rs = Database::search(" SELECT * FROM `promo_item` WHERE `product_id` = '" . $pid . "' ");
        if ($promos_rs->num_rows > 0) {
            $product["promo"] = $promos_rs->fetch_assoc();
        } else {
            $product["promo"] = null;
        }

        $imgs = [];

        $imgs_rs = Database::search("SELECT * FROM `product_images` WHERE `product_id` = '" . $pid . "' AND `img_status` = '1'");
        if ($imgs_rs->num_rows > 0) {
            for ($j = 0; $j < $imgs_rs->num_rows; $j++) {
                $imgs[] = $imgs_rs->fetch_assoc();
            }
            $product["img"] = $imgs;
        } else {
            $product["img"] = null;
        }
    } else {
        $product = NULL;
    }

    return $product;
}

function checkQty($pid)
{
    $product_rs = Database::search("SELECT `qty` FROM `product` WHERE `id` = '" . $pid . "'");
    if ($product_rs->num_rows == 1) {
        $qty = $product_rs->fetch_assoc();
        return $qty["qty"];
    } else {
        return 0;
    }
}

function getDistrict($id)
{
    $distList = [];
    $dist_rs = Database::search("SELECT * FROM `districts`");
    for ($x = 0; $x < $dist_rs->num_rows; $x++) {
        $distList[] = $dist_rs->fetch_assoc();
    }
    return $distList;
}

function checkDistrict($id)
{
    $rs = Database::search("SELECT * FROM `district` WHERE `id`");
    if ($rs->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function checkProduct($id)
{
    $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = '" . $id . "'");
    if ($product_rs->num_rows == 1) {
        return $product_rs->fetch_assoc();
    } else {
        return false;
    }
}

function checkUser($id)
{
    $product_rs = Database::search("SELECT * FROM `customer` WHERE `id` = '" . $id . "'");
    if ($product_rs->num_rows == 1) {
        return true;
    } else {
        return false;
    }
}

function getUser($id)
{
    $product_rs = Database::search("SELECT * FROM `customer` WHERE `id` = '" . $id . "'");
    if ($product_rs->num_rows == 1) {
        return $product_rs->fetch_assoc();
    } else {
        return null;
    }
}

function checkOrder($oid)
{
    $rs = Database::search("SELECT * FROM `orders` WHERE `id` = '" . $oid . "' AND `order_status_id` != '5'");
    if ($rs->num_rows == 1) {
        return true;
    } else {
        return false;
    }
}

function placeOrder($fname, $lname, $mobile, $line1, $line2, $dId, $pCode, $products, $desc, $img, $shipping, $gift)
{
    global $date;

    $errors = [];
    $productsArray = [];

    if (!isset($_SESSION["u"]["id"]) || !checkUser($_SESSION["u"]["id"])) {
        $errors[] = "signIn";
    } else if (empty($products)) {
        $errors[] = "Something went wrong. Please try refreshing the page.";
    } else {
        $productsArray = json_decode($products);
        if (sizeof($productsArray) < 1) {
            $errors[] = "Something went wrong. Please try refreshing the page.";
        } else {
            $errCount = 0;
            foreach ($productsArray as $p) {
                if (!checkProduct($p->id) || $p->qty < 1) {
                    $errCount++;
                }
            }
            if ($errCount != 0) {
                $errors[] = "Something went wrong. Please try refreshing the page.";
            }
        }

        if (!filter_var($shipping, FILTER_VALIDATE_INT) || !checkShipping($shipping)) {
            $errors[] = "Something went wrong. Please try refreshing the page (Ex00008)";
            echo $shipping;
        }

        if ($gift < "0" || $gift > "1") {
            $errors[] = "Something went wrong. Please try refreshing the page (Ex00007)";
            echo $gift;
        }
    }

    if (!empty($errors) && sizeof($errors) > 0) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    } else {
        if (empty($fname) || strlen($fname) < 2 || strlen($fname) > 25) {
            $errors[] = "First name must be between 2 and 25 characters.";
        }

        if (empty($lname) || strlen($lname) < 2 || strlen($lname) > 25) {
            $errors[] = "Last name must be between 2 and 25 characters.";
        }

        $mobilePattern = '/^(071|072|075|076|077|078|070)\d{7}$/';
        if (empty($mobile) || !preg_match($mobilePattern, $mobile)) {
            $errors[] = "Invalid mobile number";
        }

        if (empty($line1)) {
            $errors[] = "Address line 1 cannot be empty.";
        }

        if (empty($line2)) {
            $errors[] = "Address line 2 cannot be empty.";
        }

        if (empty($dId)) {
            $errors[] = "Select a district.";
        }

        if (empty($pCode) || !preg_match('/^\d{5}$/', $pCode)) {
            $errors[] = "Invalid postal code.";
        }

        if (!empty($errors) && sizeof($errors) > 0) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        } else {

            $user = getUser($_SESSION["u"]["id"]);

            if (!checkUploaded($img)) {
                echo "Failed upload the file";
            } else {

                $orderIn = Database::iud("INSERT INTO `orders` (`date`,`fname`,`lname`,`mobile`,`addressLine1`,`addressLine2`,`postal_code`,`order_status_id`,`customer_id`,`district_id`,`gift`,`shipping_id`)
                   VALUES ('" . $date . "','" . $fname . "','" . $lname . "','" . $mobile . "','" . $line1 . "','" . $line2 . "','" . $pCode . "','2','" . $_SESSION["u"]["id"] . "','" . $dId . "','" . $gift . "','" . $shipping . "') ");

                if ($orderIn) {
                    addOrderProducts($productsArray, $orderIn);
                    addOrderPayment($img, $orderIn);
                    echo "success";
                } else {
                    echo "Failed to place order. Please contact support team.";
                }
            }
        }
    }
}

function addOrderProducts($pids, $order_id)
{
    foreach ($pids as $pObj) {
        $product = getProduct($pObj->id);
        if ($product) {
            $price = "";
            if ($product["promo"]) {
                $price = $product["promo"]["new_price"];
            } else {
                $price = $product["price"];
            }
            $insert = Database::iud("INSERT INTO `order_item` (`product_id`,`price`,`qty`,`order_id`) VALUES ('" . $product["id"] . "','" . $price . "','" . $pObj->qty . "','" . $order_id . "')");

            if (!$insert) {
                return "Something went wrong. Please contact support team";
            } else {
                return true;
            }
        } else {
            return "Something went wrong. Please contact support team.";
        }
    }
}

function checkShipping($id)
{
    $shipping_rs = Database::search("SELECT * FROM `shipping` WHERE `id` = '" . $id . "'");
    if ($shipping_rs->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function addOrderPayment($img, $order_id)
{
    global $date;

    $new_img_extention = "";

    $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/svg+xml");

    if (isset($img) && file_exists($img["tmp_name"])) {

        $file_extention = $img["type"];

        if (in_array($file_extention, $allowed_image_extentions)) {

            if ($file_extention == "image/jpg") {
                $new_img_extention = ".jpg";
            } else if ($file_extention == "image/jpeg") {
                $new_img_extention = ".jpeg";
            } else if ($file_extention == "image/png") {
                $new_img_extention = ".png";
            } else if ($file_extention == "image/svg+xml") {
                $new_img_extention = ".svg";
            }

            $order = checkOrder($order_id);

            if ($order) {
                $file_name = $order_id . $new_img_extention;
                Database::search("INSERT INTO `order_payments` (`img`,`date`,`order_id`) VALUES ('" . $file_name . "','" . $date . "','" . $order_id . "')");
                move_uploaded_file($img["tmp_name"], "../_resources/img/orders/" . $file_name);
            } else {
                echo "Something went wrong";
            }
        } else {
            echo ("Not an allowed image type");
        }
    } else {
        echo "Please upload product Image";
    }
}

function getOrders($uid, $oid, $status, $name)
{
    global $imgQ;

    $q = "SELECT o.id as 'oid', o.order_status_id as 'status', o.date as 'date',o.fname, o.lname, p.id as 'pid', p.title, oi.price, oi.qty, c.id as cid, pi.img, c.email as email, s.title as shipMethod, s.cost as shipping
    FROM `orders` AS o
    INNER JOIN `customer`AS c ON `c`.`id` = `o`.`customer_id`
    INNER JOIN `order_item` AS oi ON `o`.`id` = `oi`.`order_id`
    INNER JOIN `product` AS p ON `oi`.`product_id` = `p`.`id`
    INNER JOIN `shipping` AS s ON `o`.`shipping_id` = `s`.`id`
    LEFT JOIN (" . $imgQ . ") AS pi ON `p`.`id` = `pi`.`product_id` ";

    $set = false;

    if (!empty($uid) && filter_var($uid, FILTER_VALIDATE_INT)) {
        $q .= (!$set ? " WHERE " : " AND ") . "`o`.`customer_id` = '" . $uid . "'";
        $set = true;
    }

    if (!empty($oid) && filter_var($oid, FILTER_VALIDATE_INT)) {
        $q .= (!$set ? " WHERE " : " AND ") . "`o`.`id` = '" . $oid . "'";
        $set = true;
    }

    if (!empty($status) && filter_var($status, FILTER_VALIDATE_INT)) {
        $q .= (!$set ? " WHERE " : " AND ") . "`o`.`order_status_id` = '" . $status . "'";
        $set = true;
    }

    $q .= " ORDER BY `o`.`date` DESC";

    $order_rs = Database::search($q);

    if ($order_rs->num_rows > 0) {

        $orderId = 0;
        $orders = [];

        for ($i = 0; $i < $order_rs->num_rows; $i++) {
            $order = $order_rs->fetch_assoc();
            if ($order["oid"] != $orderId) {
                $orders[$order["oid"]] = [
                    "oid" => $order["oid"],
                    "status" => $order["status"],
                    "fname" => $order["fname"],
                    "lname" => $order["lname"],
                    "products" => [],
                    "cid" => $order["cid"],
                    "date" => $order["date"],
                    "total" => "0",
                    "shipMethod" => $order["shipMethod"],
                    "shipping" => $order["shipping"]
                ];
                $orderId = $order["oid"];
            }
            $orders[$order["oid"]]["products"][] = [
                "pid" => $order["pid"],
                "title" => $order["title"],
                "price" => $order["price"],
                "img" => $order["img"],
                "qty" => $order["qty"]
            ];

            $orders[$order["oid"]]["total"] = $orders[$order["oid"]]["total"] + ($order["price"] * $order["qty"]);
        }

        return $orders;
    } else {
        return null;
    }
}

function renderCustomerOrders($dataSet)
{
    if (is_array($dataSet) && sizeof($dataSet) > 0) {
        foreach ($dataSet as $data) {
        ?>
            <div class="row pt-3 mt-3 mb-1 flex-wrap profile--orderCard">
                <div class="col-8 col-lg-9">
                    <div class="row flex-wrap">
                        <div class="col-12 col-lg-6">
                            <span>Order id : 00<?php echo $data["oid"]; ?></span>
                        </div>
                        <div class="col-12 col-lg-6 text-start text-lg-end">
                            <span class="d-lg-none">Order date :</span><span><?php echo $data["date"]; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-lg-3 d-flex flex-column align-items-center justify-content-center border-start border-1 border-secondary">
                    <!-- <img src="../home/resources/star.png" class="profile--orderStatusImg"> -->

                    <?php
                    if ($data["status"] == "1") {
                    ?>
                        <span class="profile--orderStatusText text-black fw-bold">
                            <i class="bi bi-cash-coin"></i>
                            Unpaid
                        </span>
                    <?php
                    } else if ($data["status"] == "2") {
                    ?>
                        <span class="profile--orderStatusText text-black fw-bold">
                            <i class="bi bi-clock-history"></i>
                            Pending
                        </span>
                    <?php
                    } else if ($data["status"] == "3") {
                    ?>
                        <span class="profile--orderStatusText text-success fw-bold">
                            <i class="bi bi-check2"></i>
                            Accepted
                        </span>
                    <?php
                    } else if ($data["status"] == "4") {
                    ?>
                        <span class="profile--orderStatusText text-primary fw-bold">
                            <i class="bi bi-bag-check-fill"></i>
                            Shipped
                        </span>
                    <?php
                    } else if ($data["status"] == "5") {
                    ?>
                        <span class="profile--orderStatusText text-danger fw-bold">
                            <i class="bi bi-x-circle"></i>
                            Declined
                        </span>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-12 mt-1 profile--ocProducts">
                    <div class="row px-3 profile--ocBar">
                        <div class="col-6">
                            <span>Product</span>
                        </div>
                        <div class="col-3">
                            <span>Qty</span>
                        </div>
                        <div class="col-3">
                            <span>Price</span>
                        </div>
                    </div>
                    <?php
                    foreach ($data["products"] as $product) {
                    ?>
                        <div class="row m-2 profile--ocProduct" style="height: 5vh;">
                            <div class="col-6 d-flex flex-column justify-content-center border-right border-1">
                                <span class="profile--cardTitle"><?php echo $product["title"]; ?></span>
                            </div>
                            <div class="col-3 d-flex flex-column justify-content-center border-right border-1">
                                <span class="profile--cardTitle"><?php echo $product["qty"]; ?></span>
                            </div>
                            <div class="col-3 d-flex flex-column justify-content-center">
                                <span class="profile--cardTitle">Rs. <?php echo $product["price"]; ?>.00</span>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="row text-end fw-bold fs-5 profile--ocFooter">
                        <span>Total : Rs. <?php echo $data["total"]; ?>.00</span>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
<?php
    }
}

function checkUploaded($file)
{
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        return true;
    } else {
        return false;
    }
}

function loadCart($user, $pid)
{
    global $imgQ;

    $q = "SELECT *, c.qty as cartQty
    FROM `cart` AS c
    INNER JOIN `product` AS p ON `c`.`product_id` = `p`.`id`
    LEFT JOIN `promo_item` AS pro_i ON `p`.`id` = `pro_i`.`product_id`
    LEFT JOIN (" . $imgQ . ") AS pi ON `p`.`id` = `pi`.`product_id`";
    $errors = [];

    if (empty($user) || !checkUser($user)) {
        $errors[] = "Invalid user id found. Please refresh the page";
    } else {
        $q .= " WHERE `customer_id` = '" . $user . "'";
    }

    if (!empty($pid)) {
        if (checkProduct($pid)) {
            $q .= " AND `c`.`product_id` = '" . $pid . "'";
        } else {
            $errors[] = "Invalid product Id found. Please refresh the page";
        }
    }

    if (sizeof($errors) > 0) {
        foreach ($errors as $err) {
            echo $err;
        }
    } else {
        $cart = [];
        $cart_rs = Database::search($q);
        if ($cart_rs->num_rows == 0) {
            return null;
        } else {
            for ($i = 0; $i < $cart_rs->num_rows; $i++) {
                $cart[$i] = $cart_rs->fetch_assoc();
            }
            return $cart;
        }
    }
}

function addToCart($pid, $qty, $uid)
{
    $errors = [];

    if (!checkUser($uid)) {
        $errors[] = "Invalid user id found.";
    }

    $product = checkProduct($pid);

    if (empty($pid) || $product == null) {
        $errors[] = "Invalid product id found.";
    } else {

        if ($product["qty"] == "0") {
            $errors[] = "Cannot add this product to cart";
        }
    }

    if (sizeof($errors) > 0) {
        foreach ($errors as $err) {
            echo $err;
        }
    } else {
        $cart_rs = loadCart($uid, $pid);
        if ($cart_rs) {
            if ($cart_rs[0]["qty"] < ($cart_rs[0]["cartQty"] + $qty)) {
                echo "Product quantity limit reached";
            } else {
                $newQty = $cart_rs[0]["cartQty"] + $qty;
                Database::iud("UPDATE `cart` SET `qty` = '" . $qty . "' WHERE `product_id` = '" . $pid . "' AND `customer_id` = '" . $uid . "'");
                echo "success";
            }
        } else {
            Database::iud("INSERT INTO `cart` (`qty`,`product_id`,`customer_id`) VALUES ('" . $qty . "','" . $pid . "','" . $uid . "')");
            echo "success";
        }
    }
}

function rmvFromCart($pid, $qty, $uid)
{
    $errors = [];

    if (!checkUser($uid)) {
        $errors[] = "Invalid user id found.";
    }

    $product = checkProduct($pid);

    if (empty($pid) || $product == null) {
        $errors[] = "Invalid product id found.";
    }

    if (sizeof($errors) > 0) {
        foreach ($errors as $err) {
            echo $err;
        }
    } else {
        $cart_rs = loadCart($uid, $pid);
        if ($cart_rs) {
            if ($cart_rs[0]["cartQty"] > $qty) {
                $newQty = $cart_rs[0]["cartQty"] - $qty;
                Database::iud("UPDATE `cart` SET `qty` = '" . $qty . "' WHERE `product_id` = '" . $pid . "' AND `customer_id` = '" . $uid . "'");
                echo "success";
            } else {
                Database::iud("DELETE FROM `cart` WHERE `product_id` = '" . $pid . "' AND `customer_id` = '" . $uid . "'");
                echo "success";
            }
        } else {;
            echo "No such product in cart. Please refresh the page";
        }
    }
}

function getCartProducts()
{
    $cart_rs = loadCart($_SESSION["u"]["id"], "");
    $productArray = [];
    if ($cart_rs) {
        for ($i = 0; $i < sizeof($cart_rs); $i++) {
            $rec = $cart_rs[$i];
            $productArray[] = [
                "id" => $rec["product_id"],
                "qty" => $rec["cartQty"]
            ];
        }
    }
    return json_encode($productArray);
}
