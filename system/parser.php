<h1>Парсер</h1>
<button class="btn btn-warning" id="export">Експорт в CSV</button>
<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
   Виберіть Тип Записів <span class="glyphicon glyphicon-arrow-down"></span>
</a>
<div class="collapse" id="collapseExample">
  <div class="well">
    <?php show_parser_post_typs()?>
    <br>
    <label for="search-by-id">Шукати по ID</label>
    <input type="text" id="search-by-id">
  </div>
</div>
<?php parser_pagination(array('post_types'=>array('post','page'),'limit'=>100))?>
<div class="table-block">
<table class="parse-table table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>ANCHOR<span class="glyphicon glyphicon-search"></span>
				<div class="search-input form-group">
				<input type="text" class="form-control" data-type='anchor'>
				</div>
			</th>
			<th>URL<span class="glyphicon glyphicon-search"></span>
				<div class="search-input form-group">
				<input type="text" class="form-control" data-type='url'>
				</div>
			</th>
			<th>PAGE<span class="glyphicon glyphicon-search"></span>
				<div class="search-input form-group">
				<input type="text" class="form-control" data-type='page'>
				</div>
			</th>
			<th>REL<span class="glyphicon glyphicon-search"></span>
				<div class="search-input form-group">
				<select class="form-control" data-type='rel'>
					<option value="0"></option>
					<option value="dofollow">dofollow</option>
					<option value="nofollow">nofollow</option>
				</select>
				</div>
			</th>
			<th>TAR<span class="glyphicon glyphicon-search"></span>
				<div class="search-input form-group">
				<select class="form-control" data-type='target'>
					<option value="0"></option>
					<option value="_blank">_blank</option>
					<option value="_self">_self</option>
				</select>
				</div>
			</th>
			<th>			
				<button class="btn btn-primary" id="search">Шукати</button>
			</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>
	<!-- <button class="btn btn-info prev" data-paged='1'><span class="glyphicon glyphicon-arrow-left"></span></button>
	<button class="btn btn-info next" data-paged='1'><span class="glyphicon glyphicon-arrow-right"></span></button> -->
	<div class="parse-pagination"></div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="showcvs" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Завантажити CSV</h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>