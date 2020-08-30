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
									<h3 class="box-title">Deleted Products</h3>
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
												<td>
													<?php echo anchor('admin/products/restore/'.$product->id, 'Restore',array('class' => 'btn btn-primary btn-xs')); ?>&nbsp;|&nbsp;<?php echo anchor('admin/products/realdelete/'.$product->id, 'Delete',array('class' => 'btn btn-danger btn-xs','onclick' => "return confirm('Do you want delete this record permanently')")); ?>
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
