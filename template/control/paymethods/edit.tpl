<div class="row">
<div class="col-sm-12">
<h4 class="page-title">Изменение платежной системы</h4>
<ol class="breadcrumb">
<li><a href="/control">Главная</a></li>
<li><a href="/control/paymethods">Способы оплаты</a></li>
<li class="active">Изменение платежной системы</li>
</ol>
</div>
</div>

<div class="col-sm-12">
<div class="card-box">
<h4 class="m-t-0 header-title"><b>Изменение платежной системы</b></h4>

<div class="row">
<form action="#" id="servicesForm" method="post">
<div class="col-md-6">
<div class="form-group">
<label for="status">Статус</label>
<select name="status" class="form-control" id="status">
<option value="1">Включено</option>
<option value="0" <?php if($data['status'] == '0'):?>selector<?php endif;?>>Отключено</option>
</select>
</div>

<div class="form-group text-right m-b-0">
<button class="btn btn-warning waves-effect waves-light" type="submit">
Изменить
</button>


</div>
</div>

<div class="col-md-6">
<?php if($data['typeCode'] == 'robokassa'):?>
<div class="form-group">
<label for="status">login</label>
<input type="text" class="form-control" name="login" value="<?=$params['login'];?>">
</div>

<div class="form-group">
<label for="password1">password 1</label>
<input type="text" class="form-control" id="password1" name="password1" value="<?=$params['password1'];?>">
</div>

<div class="form-group">
<label for="password2">password 2</label>
<input type="text" class="form-control" id="password2" name="password2" value="<?=$params['password2'];?>">
</div>

<code>
Обработчик: <?php echo $url;?>/result?type=robokassa
</code>

<?php elseif($data['typeCode'] == 'unitpay'):?>
<div class="form-group">
<label for="status">PUBLIC KEY</label>
<input type="text" class="form-control" name="public_key" value="<?=$params['public_key'];?>">
</div>

<div class="form-group">
<label for="status">SECRET KEY</label>
<input type="text" class="form-control" name="secret_key" value="<?=$params['secret_key'];?>">
</div>

<code>
Обработчик: <?php echo $url;?>/result?type=unitpay
</code>

<?php endif;?>
</div>

</div>
</form>




										

        				                        				
</div>

</div>
<script>
$('#servicesForm').ajaxForm({
   dataType: 'json',
   success: function(data) {
     switch(data.status){
        case "error":
        ShowModal(data.error, 'answer', 'error');
        break;
        
        case "success":
        ShowModal(data.success, 'answer', 'success');
        break;
     }
   },                          
}); 


function services(){
    var type = $("#servicesType").val();
    if(type == 'color'){
        $("#moreParams").show();
    }else{
        $("#moreParams").hide();
    }
}
</script>
