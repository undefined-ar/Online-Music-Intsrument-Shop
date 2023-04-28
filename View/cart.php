<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<div class="instruments-cart-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12 clearfix">
                <h2 class="section-head">My Cart</h2>
                <?php
                    if(isset($_COOKIE['user_cart'])){
                        $pid = json_decode($_COOKIE['user_cart']);
                        if(is_object($pid)){
                            $pid = get_object_vars($pid);
                        }
                        $pids = implode(',',$pid);
                        $db = new Database();
                        $db->select('instruments','*',null,'instruments_id IN ('.$pids.')',null,null);
                        $result = $db->getResult();
                        if(count($result) > 0){ ?>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>Instruments Image</th>
                                    <th>Instruments Name</th>
                                    <th width="120px">Instruments Price</th>
                                    <th width="100px">Qty.</th>
                                    <th width="100px">Sub Total</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>
                                <?php foreach($result as $row){ ?>
                                    <tr class="item-row">
                                        <td><img src="instruments-images/<?php echo $row['featured_image']; ?>" alt="" width="70px" /></td>
                                        <td><?php echo $row['instruments_title']; ?></td>
                                        <td><?php echo $cur_format; ?> <span class="instruments-price"><?php echo $row['_price']; ?></span></td>
                                        <td>
                                            <input class="form-control item-qty" type="number" value="1"/>
                                            <input type="hidden" class="item-id" value="<?php echo $row[instruments_id']; ?>"/>
                                            <input type="hidden" class="item-price" value="<?php echo $row['instruments_price']; ?>"/>
                                        </td>
                                        <td><?php echo $cur_format; ?> <span class="sub-total"><?php echo $row['instruments_price']; ?></span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary remove-cart-item" href="" data-id="<?php echo $row['instruments_id']; ?>"><i class="fa fa-remove"></i></a>
                                        </td>
                                    </tr>
                        <?php    } ?>
                                    <tr>
                                        <td colspan="5" align="right"><b>TOTAL AMOUNT (<?php echo $cur_format; ?>)</b></td>
                                        <td class="total-amount"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <a class="btn btn-sm btn-primary" href="<?php echo $hostname; ?>" >Continue Shopping</a>
                                <?php if(isset($_SESSION['instruments_role'])){ ?>

                                <form action="instamojo.php" class="checkout-form pull-right" method="POST">
                                    <?php
                                        $instruments_id = '';
                                        foreach($result as $row){
                                            $instruments_id .= $row['instruments_id'].',';
                                        }
                                    ?>
                                    <input type="hidden" name="instruments_id" value="<?php echo $instruments_id; ?>">
                                    <input type="hidden" name="instruments_total" class="total-price" value="">
                                    <input type="hidden" name="instruments_qty" class="total-qty" value="1">
                                    
                                </form>
                                <?php }else{ ?>
                                    <a class="" href="#" data-toggle="modal" data-target="#userLogin_form" ></a>
                                <?php } ?>
                <?php   }
                    }else{ ?>
                        <div class="empty-result">
                            Your cart is currently empty.
                        </div>
                    <?php }
                ?>


            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>