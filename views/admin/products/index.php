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
									<h3 class="box-title"><?php echo anchor('admin/products/create', '<i class="fa fa-plus"></i> '. 'Create Product', array('class' => 'btn btn-block btn-primary btn-flat')); ?></h3>
								</div>
								<div class="box-body">
                                    <?php echo $message;?>
                                    <table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>ID</th>
												<th>Image</th>
												<th>Title</th>
												<th>Price</th>
												<th>Sku</th>
												<th>Category</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
                                        <?php if($products):?>
                                            <?php foreach ($products as $product):?>
                                                <?php if($product->image != ""){$imageFile = base_url('upload/products/'. $product->image);}else{$imageFile = 'https://via.placeholder.com/50x50.png?text=No+Image';} ?>
											<tr>
												<td><?php echo $product->id;?></td>
												<td><img src="<?php echo $imageFile;?>" height="50"></td>
                                                <td><?php echo $product->title;?></td>
                                                <td>$<?php echo $product->price;?></td>
												<td><?php echo $product->sku;?></td>
												<td><?php echo getCategoryById($product->category);?></td>
                                                <td><?php echo ($product->status) ? anchor('admin/products/status/'.$product->id, '<span class="label label-success">'.'Active'.'</span>') : anchor('admin/products/status/'. $product->id, '<span class="label label-default">'.'Inactive'.'</span>'); ?></td>
												<td>
													<?php echo anchor('admin/products/edit/'.$product->id, 'Edit',array('class' => 'btn btn-primary btn-xs')); ?>&nbsp;|&nbsp;<?php echo anchor('admin/products/delete/'.$product->id, 'Delete',array('class' => 'btn btn-danger btn-xs','onclick' => "return confirm('Do you want delete this record')")); ?>
												</td>
											</tr>
                                            <?php endforeach;?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No record found!</td>
                                            </tr>
                                        <?php endif;?>
										</tbody>
									</table>
								</div>
							</div>
						 </div>
					</div>
				</section>
			</div>
