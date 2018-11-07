<script type="text/javascript">
$(document).ready(function(){
  $(':input:not([type="submit"])').each(function() {
    $(this).focus(function() {
      $(this).addClass('hilite');
    }).blur(function() {
      $(this).removeClass('hilite');});
  });


  $("#view").show();
  $("#form").hide();

    $("#tambah").click(function(){
      $("#view").hide();
      $("#form").show();
      $("#nama_client").focus();
      return false();
    });

  function kosong(){
    $("#id_client").val('');
    $("#nama_client").val('');
    $("#alamat").val('');
	$("#telp").val('');
    return false();
  }
  
  $("#simpan").click(function(){
    var id_client = $("#id_client").val();
    var nama_client = $("#nama_client").val();
	var alamat = $("#alamat").val();
	var telp = $("#telp").val();

    var string = $("#my-form").serialize();
    //alert(string);

    if(nama_client.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Nama Client Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#nama_client").focus();
      return false;
    }
    if(alamat.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Alamat Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#alamat").focus();
      return false;
    }
	if(telp.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Telepon Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#telp").focus();
      return false;
    }

    $.ajax({
      type	: 'POST',
      url		: "<?php echo site_url(); ?>/client/simpan",
      data	: string,
      cache	: false,
      success	: function(data){
        $.messager.show({
          title:'Info',
          msg:data,
          timeout:2000,
          showType:'slide'
        });
      },
      error : function(xhr, teksStatus, kesalahan) {
        $.messager.show({
          title:'Info',
          msg: 'Server tidak merespon :'+kesalahan,
          timeout:2000,
          showType:'slide'
        });
        return false();
      }
    });
    return false();
  });

  $("#tambah_data").click(function(){
    $.ajax({
      type	: 'POST',
      url		: "<?php echo site_url(); ?>/master/tambah",
      cache	: false,
      success	: function(data){
        kosong();
        $("#nama_client").focus();
      }
    });
    return false();
  });

  $("#kembali").click(function(){
    window.location.assign('<?php echo base_url();?>index.php/client');
    return false();
  });
});

function editData(id){
  var string = "id="+id;
  $.ajax({
      type	: 'POST',
      url		: "<?php echo site_url(); ?>/client/edit",
      data	: string,
      cache	: true,
      dataType : "json",
      success	: function(data){
        $("#view").hide();
        $("#form").show();

        $("#nama_client").focus();

        $("#id_client").val(id);
        $("#nama_client").val(data.nama_client);
        $("#alamat").val(data.alamat);
		$("#telp").val(data.telp);
        return false();
      }
  });
}
</script>
<div id="view">

    <div style="float:left; padding-bottom:5px;">
    <button type="button" name="tambah" id="tambah" class="easyui-linkbutton" data-options="iconCls:'icon-add'">Tambah Data</button>

    <a href="<?php echo base_url();?>index.php/client">
    <button type="button" name="refresh" id="refresh" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">Refresh</button>
    </a>
    </div>
    <div style="float:right; padding-bottom:5px;">
    <form name="form" method="post" action="<?php echo base_url();?>index.php/client">
    Cari ID Client/Nama Client : <input type="text" name="txt_cari" id="txt_cari" size="50" />
    <button type="submit" name="cari" id="cari" class="easyui-linkbutton" data-options="iconCls:'icon-search'">Cari</button>
    </form>
    </div>

<div style="padding:10px;"></div>
<table id="dataTable" width="100%">
<tr>
  <th>No</th>
    <th>ID Client</th>
    <th>Nama Client</th>
    <th>Alamat</th>
	<th>Telpon</th>
    <th>Aksi</th>
</tr>
<?php
  if($data->num_rows()>0){
    $no =1+$hal;
    foreach($data->result_array() as $db){
    ?>
      <tr>
      <td align="center" width="20"><?php echo $no; ?></td>
            <td align="center" width="150" ><?php echo $db['id_client']; ?></td>
            <td ><?php echo $db['nama_client']; ?></td>
            <td ><?php echo $db['alamat']; ?></td>
			<td ><?php echo $db['telp']; ?></td>
            <td align="center" width="80">
            <?php echo "<a href='javascript:editData(\"{$db['id_client']}\")'>";?>
      <img src="<?php echo base_url();?>asset/images/ed.png" title='Edit'>
      </a>
            <a href="<?php echo base_url();?>index.php/client/hapus/<?php echo $db['id_client'];?>"
            onClick="return confirm('Anda yakin ingin menghapus data ini?')">
      <img src="<?php echo base_url();?>asset/images/del.png" title='Hapus'>
      </a>
            </td>
    </tr>
    <?php
    $no++;
    }
  }else{
  ?>
      <tr>
          <td colspan="6" align="center" >Tidak Ada Data</td>
        </tr>
    <?php
  }
?>
</table>
<?php echo "<table align='center'><tr><td>".$paginator."</td></tr></table>"; ?>

</div>
<div id="form">
<form name="my-form" id="my-form">
<fieldset class="atas">
<table width="100%">
<tr>
  <td width="10%">ID Client</td>
    <td width="5">:</td>
    <td><input type="text" name="id_client" id="id_client" size="12" value="<?php echo $kodeclient; ?>" readonly='readonly' /></td>
</tr>
<tr>
  <td width="10%">Nama Client</td>
    <td width="5">:</td>
    <td><input type="text" name="nama_client" id="nama_client" size="30" maxlength="100" /></td>
</tr>
<tr>
  <td>Alamat</td>
    <td>:</td>
    <td><input type="text" name="alamat" id="alamat" size="30" maxlength="1000" /></td>
</tr>
<tr>
  <td>Telepon</td>
    <td>:</td>
    <td><input type="text" name="telp" id="telp"  size="30" maxlength="12" /></td>
</tr>  
</table>
</fieldset>
<fieldset class="bawah">
<table width="100%">
<tr>
  <td colspan="3" align="center">
    <button type="button" name="simpan" id="simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save'">SIMPAN</button>
    <button type="button" name="tambah_data" id="tambah_data" class="easyui-linkbutton" data-options="iconCls:'icon-add'">TAMBAH</button>
    <button type="button" name="kembali" id="kembali" class="easyui-linkbutton" data-options="iconCls:'icon-back'">KEMBALI</button>
    </td>
</tr>
</table>
</fieldset>
</form>
</div>
