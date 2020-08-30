<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Edit Product</h3>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open_multipart(uri_string(), array('class' => 'form-horizontal', 'id' => 'form-edit_product')); ?>
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Product Title</label>
                                        <div class="col-sm-10">
                                            <?php echo form_input($title);?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="sku" class="col-sm-2 control-label">Product Sku</label>
                                        <div class="col-sm-10">
                                            <?php echo form_input($sku);?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="price" class="col-sm-2 control-label">Product Price</label>
                                        <div class="col-sm-10">
                                            <?php echo form_input($price);?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="price" class="col-sm-2 control-label">Select Category</label>
                                        <div class="col-sm-10">
                                            <?php echo form_dropdown('category', $categories, $category, array('class' => 'form-control'));?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-sm-2 control-label">Product Description</label>
                                        <div class="col-sm-10">
                                            <?php echo form_textarea($description);?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image" class="col-sm-2 control-label">Uploaded Image</label>
                                        <div class="col-sm-10">
                                            <?php if($product->image != ""){$imageFile = base_url('upload/products/'. $product->image);}else{$imageFile = 'https://via.placeholder.com/50x50.png?text=No+Image';} ?>
                                            <img src="<?php echo $imageFile;?>" height="100">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image" class="col-sm-2 control-label">Product Image</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="image" id="image">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Product Active</label>
                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="status" value="1" <?php echo set_value('status', $product->status) == 1 ? 'checked' : NULL; ?>> Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="status" value="0" <?php echo set_value('status', $product->status) == 0 ? 'checked' : NULL; ?>> No
                                            </label>
                                        </div>
                                    </div>


                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?php echo form_hidden('id', $product->id);?>
                                                <div class="btn-group">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => "Update")); ?>
                                                    <?php echo anchor('admin/products', 'Cancel', array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
