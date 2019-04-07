<div class="name-block">
<i class="fa fa-star-o"></i> Топ сервера
</div>

<div class="top-servers">

<?php foreach($topServers as $row):?>
<div class="item">
<div class="hostname"><a href="<?php if($row['id'] != null):?>/server/info?id=<?php echo $row['id'];?><?php else:?>/pay<?php endif;?>"><?php echo $row['hostname'];?></a></div>
<div class="image-map">
<img src="<?php echo $row['img_map'];?>"/>
<div class="name-map"><?php echo $row['map'];?></div>
</div>

<div class="info">
<div class="players">
Игроки: <i class="fa fa-users"></i> <?php echo $row['players'];?>/<?php echo $row['max_players'];?>
</div>

<div class="address">
<?php echo $row['ip'];?>:<?php echo $row['port'];?>
</div>

</div>
</div>

<?php endforeach;?>


</div>
<div class="clearfix"></div>
