<!-- Breadcome start-->
<div class="breadcome-area mg-b-30 small-dn">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="breadcome-heading">
                                <h2>
                                    <img src="<?php echo base_url('public/img/user.png'); ?>" />
                                    &nbsp;Welcome <?php echo ucwords($username); ?>
                                </h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Category</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcome End-->
<div class="welcome-adminpro-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                    <div class="welcome-adminpro-title">
                        <h1>Create New Category</h1>
                        <p>Enter the required details to create the Category.</p>
                    </div>
                    <div class="adminpro-message-list">
                        <div class="alert"></div>
                        <form name="createcategory" id="createCategory" method="post">


                            <div class="form-group">
                                <label for="categoryname">Category Name *</label>
                                <input type="text" class="form-control" name="category_name" id="cateName"
                                    list="categorydrop" placeholder="Enter the category name"
                                    value="<?= $category_name ?>" autocomplete="off" />

                            </div>

                            <div class="form-group">
                                <label for="buildname">Sub Category *</label>
                                <input type="text" class="form-control" name="subcategory_name" id="subCate"
                                    placeholder="Enter the sub category" value="<?= $subcategory_name ?>" />
                            </div>
                            <div class="form-group">
                                <label for="buildname">Diminishing Rate(%) *</label>
                                <input type="text" class="form-control" name="diminish_rate" id="dimiRate"
                                    placeholder="Enter the diminish rate" value="<?= $diminish_rate ?>"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>

                            <div class="form-group text-right">
                                <input type="hidden" name="cat_id"
                                    value="<?php echo (isset($catId) && $catId!="" ? $catId : 0); ?>" />
                                <?php 
											if(isset($catId) && $catId!="") {
											?>
                                <a href="<?= base_url('category')?>"><button type="button"
                                        class="btn btn-secondary">Cancel</button></a>
                                <?php 
											}
											?>
                                <button type="button" name="category-btn" id="category-btn" class="btn btn-primary">
                                    <?php 
											if(isset($catId) && $catId!="") {
												echo "Update";
											}
											else {
												echo "Create";
											}
											?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Listed Category and Sub Category</h1>
                        </div>
                    </div>

                    <div class="sparkline8-graph">
                        <div class="category-list custom-datatable-overright">
                            <table id="categoryTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Slno</th>
                                        <th>Categories & Subcategories</th>
                                        <th>Diminish Rate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>



            <!---->
        </div>
    </div>
</div>
<!-- welcome Project, sale area start-->

</div>
</div>