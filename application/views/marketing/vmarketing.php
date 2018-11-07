<script type="text/javascript">
$(document).ready(function(){
  $(':input:not([type="submit"])').each(function() {
    $(this).focus(function() {
      $(this).addClass('hilite');
    }).blur(function() {
      $(this).removeClass('hilite');});
  });

  $('.tanggal').datebox({
      required:true
  });
  
  $("#tgl_penawaran").datepicker({
			dateFormat:"dd-mm-yy"
    });
	
  $("#tgl_terima").datepicker({
			dateFormat:"dd-mm-yy"
    });

  $("#tgl_pemasangan").datepicker({
			dateFormat:"dd-mm-yy"
    });

  $("#view").show();
  $("#form").hide();

    $("#tambah").click(function(){
      $("#view").hide();
      $("#form").show();
      $("#no_po").focus();
      return false;
    });

  function kosong(){
    $("#no_po").val('');
    $("#tgl_po").val('');
    $("#id_client").val('');
    $("#no_spk").val('');
	$("#no_pesanan").val('');
	$("#tgl_penawaran").val('');
	$("#tgl_terima").val('');
	$("#pekerjaan").val('');
	$("#ukuran").val('');
	$("#jumlah").val('');
	$("#warna").val('');
	$("#bahan").val('');
	$("#proses").val('');
	$("#ketentuan").val('');
	$("#tgl_pemasangan").val('');
	$("#alamat_pasang").val('');
	$("#harga").val('');
	$("#ppn").val('');
	$("#total").val('');
	return false();
  }
  $("#simpan").click(function(){
    var no_po = $("#no_po").val();
	var tgl_po = $("#tgl_po").val();
	var id_client = $("#id_client").val();
	var no_spk = $("#no_spk").val();
	var no_penawaran = $("#no_penawaran").val();
	

    var string = $("#my-form").serialize();
    //alert(string);
	if(id_client.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Nama Pemesan / Client Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#id_client").focus();
      return false;
    }
	if(no_spk.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Surat Pesanan / SPK No Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#no_spk").focus();
      return false;
    }
	if(no_penawaran.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, No Surat Penawaran Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#no_penawaran").focus();
      return false;
    }
	if(tgl_penawaran.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Tanggal Penawaran Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#tgl_penawaran").focus();
      return false;
    }
	if(tgl_terima.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Tanggal Terima PT.Neonlite Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#tgl_terima").focus();
      return false;
    }
	if(pekerjaan.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Pesanan / Pekerjaan Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#pekerjaan").focus();
      return false;
    }
	if(ukuran.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Ukuran Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#ukuran").focus();
      return false;
    }
	if(jumlah.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Jumlah Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#jumlah").focus();
      return false;
    }
	if(warna.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Warna Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#warna").focus();
      return false;
    }
	if(proses.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Proses Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#proses").focus();
      return false;
    }
	if(ketentuan.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Ketentuan Lain Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#ketentuan").focus();
      return false;
    }
	if(tgl_pemasangan.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Tanggal Pemasangan Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#tgl_pemasangan").focus();
      return false;
    }
	if(alamat_pasang.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Alamat Pemasangan Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#alamat_pasang").focus();
      return false;
    }
	if(harga.length==0){
      $.messager.show({
        title:'Info',
        msg:'Maaf, Harga Tidak Boleh Kosong',
        timeout:2000,
        showType:'slide'
      });
      $("#harga").focus();
      return false;
    }
    
    //if(nama.length==0){
    //  $.messager.show({
    //    title:'Info',
    //    msg:'Maaf, Nama Lengkap Tidak Boleh Kosong',
    //    timeout:2000,
    //    showType:'slide'
    //  });
    //  $("#nama").focus();
    //  return false;
    //}

    $.ajax({
      type	: 'POST',
      url		: "<?php echo site_url(); ?>/marketing/simpan",
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
      url		: "<?php echo site_url(); ?>/marketing/tambah",
      cache	: false,
      success	: function(data){
        kosong();
        $("#id_client").focus();
      }
    });
    return false();
  });

  $("#kembali").click(function(){
    window.location.assign('<?php echo base_url();?>index.php/marketing');
    return false();
  });
});

function editData(id){
  var string = "id="+id;
  $.ajax({
      type	: 'POST',
      url		: "<?php echo site_url(); ?>/marketing/edit",
      data	: string,
      cache	: true,
      dataType : "json",
      success	: function(data){
        $("#view").hide();
        $("#form").show();

        //$("#username").focus();
		$("#id_client").focus();

        $("#no_po").val(id);
        $("#tgl_po").val(data.tgl_po);
        $("#id_client").val(data.id_client);
		$("#no_spk").val(data.no_spk);
        $("#no_penawaran").val(data.no_penawaran);
        $("#tgl_penawaran").val(data.tgl_penawaran);
		$("#tgl_terima").val(data.tgl_terima);
        $("#pekerjaan").val(data.pekerjaan);
        $("#ukuran").val(data.ukuran);
		$("#jumlah").val(data.jumlah);
        $("#warna").val(data.warna);
        $("#bahan").val(data.bahan);
		$("#proses").val(data.proses);
        $("#ketentuan").val(data.ketentuan);
        $("#tgl_pemasangan").val(data.tgl_pemasangan);
		$("#alamat_pasang").val(data.alamat_pasang);
        $("#harga").val(data.harga);
        $("#total").val(data.total);
		//$("#username").val(id);
        //$("#nama").val(data.nama);
        //$("#level").val(data.level);
        return false();
      }
  });
}
</script>
<div id="view">
    <div style="float:left; padding-bottom:5px;">
    <button type="button" name="tambah" id="tambah" class="easyui-linkbutton" data-options="iconCls:'icon-add'">Tambah Data</button>

    <a href="<?php echo base_url();?>index.php/marketing">
    <button type="button" name="refresh" id="refresh" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">Refresh</button>
    </a>
    </div>
    <div style="float:right; padding-bottom:5px;">
    <form name="form" method="post" action="<?php echo base_url();?>index.php/marketing">
    Cari No PO/Nama Client : <input type="text" name="txt_cari" id="txt_cari" size="50" />
    <button type="submit" name="cari" id="cari" class="easyui-linkbutton" data-options="iconCls:'icon-search'">Cari</button>
    </form>
    </div>

<div style="padding:10px;"></div>
<table id="dataTable" width="100%">
<tr>
  <th>No</th>
    <th>Tgl PO</th>
	<th>Nomor PO</th>
    <th>Nama Client</th>
    <th>Keterangan Pekerjaan</th>
	<th>Tgl Pemasangan</th>
	<th>Total Nilai</th>
    <th>Opsi</th>
</tr>
<?php
  if($data->num_rows()>0){
    $no =1+$hal;
    foreach($data->result_array() as $db){
		$tgl = $this->app_model->tgl_indo($db['tgl_po']);
		$tgl2 = $this->app_model->tgl_indo($db['tgl_pemasangan']);
		$nama_client = $this->app_model->CariNamaClient($db['id_client']);
    ?>
      <tr>
      <td align="center" width="20"><?php echo $no; ?></td>
            <td align="center" width="150" ><?php echo $tgl; ?></td>
            <td ><?php echo $db['no_po']; ?></td>
            <td ><?php echo $nama_client; ?></td>
			<td ><?php echo $db['pekerjaan']; ?></td>
			<td align="center" width="150" ><?php echo $tgl2; ?></td>
			<td ><?php echo $db['total']; ?></td>
            <td align="center" width="80">
            <?php echo "<a href='javascript:editData(\"{$db['no_po']}\")'>";?>
      <img src="<?php echo base_url();?>asset/images/ed.png" title='Edit'>
      </a>
            <a href="<?php echo base_url();?>index.php/marketing/hapus/<?php echo $db['no_po'];?>"
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
  <td colspan="6"><h2 align="center">INPUT DATA PO INTERN</h2></td>    
</tr>
<tr>
  <td width="10%">No PO Intern</td>
    <td width="5">:</td>
    <td><input type="text" name="no_po" id="no_po" size="20" value="<?php echo date('Y'), $kodeunik; ?>" readonly='readonly' /></td>
	<td width="10%">Tgl PO</td>
    <td width="5">:</td>
    <td><input type="text" name="tgl_po" id="tgl_po" size="20" value="<?php echo date('d-m-Y') ?>" readonly='readonly'/></td>
	
	
</tr>
<tr>
  <td width="15%"><h2>R E F E R E N S I</h2></td>    
</tr>
<tr>
	<td width="10%">Nama Pemesan/Client</td>
    <td width="5">:</td>
    <td>
				<select name="id_client" id="id_client" class="kosong">
				<option value="">-PILIH-</option>
				<?php
				foreach($list_client->result_array() as $t){
				?>
				<option value="<?php echo $t['id_client'];?>"><?php echo $t['id_client'];?> | <?php echo $t['nama_client'];?></option>
				<?php } ?>
				</select>
    </td>
	<td width="10%">Tanggal Penawaran</td>
    <td width="5">:</td>
    <td><input type="text" class="tanggal" name="tgl_penawaran" id="tgl_penawaran" size="30" maxlength="30" /></td>
	
	
	
</tr>
<tr>
	<td>Surat Pesanan/SPK. No</td>
    <td width="5">:</td>
    <td><input type="text" name="no_spk" id="no_spk" size="30" maxlength="30" /></td>
	<td>Diterima PT. Neonlite Tgl</td>
    <td width="5">:</td>
    <td><input type="text" class="tanggal" name="tgl_terima" id="tgl_terima" size="30" maxlength="30" /></td>
</tr>
<tr>
	<td width="10%">Surat Penawaran No</td>
    <td width="5">:</td>
    <td><input type="text" name="no_penawaran" id="no_penawaran" size="30" maxlength="30" /></td> 
  
</tr>
<tr>
  
</tr>
<tr>
  <td></td>    
</tr>
<tr>
  <td width="15%"><h2>S P E S I F I K A S I</h2></td>    
</tr>
<tr>
  <td></td>
    
</tr>
<tr>
  <td>Pesanan / Pekerjaan</td>
    <td>:</td>
    <td><textarea name="pekerjaan" id="pekerjaan"  style="width:250px; height:30px";></textarea></td>
	<td>Ketentuan Lain</td>
    <td>:</td>
    <!--<td><input type="text" name="ketentuan" id="ketentuan"  size="30" maxlength="30" /></td> -->
	<td><textarea name="ketentuan" id="ketentuan" style="width:250px; height:50px;"></textarea></td>
</tr>
<tr>
  <td>Ukuran</td>
    <td>:</td>
    <td><input type="text" name="ukuran" id="ukuran"  size="30" maxlength="30" /></td>
	<td>Waktu Penyerahan/Pemasangan</td>
    <td>:</td>
    <td><input type="text" class="tanggal" name="tgl_pemasangan" id="tgl_pemasangan"  size="30" maxlength="30" /></td>
</tr>
<tr>
  <td>Jumlah</td>
    <td>:</td>
    <td><input type="text" name="jumlah" id="jumlah"  size="30" maxlength="30" /></td>
	<td>Alamat Penyerahan/Pemasangan</td>
    <td>:</td>
    <td><textarea name="alamat_pasang" id="alamat_pasang"  style="width:250px; height:30px";></textarea></td>
</tr>
<tr>
  <td>Warna</td>
    <td>:</td>
    <td><input type="text" name="warna" id="warna"  size="30" maxlength="30" /></td>
	<td>Harga Satuan</td>
    <td>:</td>
    <td><input type="text" name="harga" id="harga"  size="30" maxlength="30" /></td>
</tr>
<tr>
  <td>Bahan</td>
    <td>:</td>
    <td><input type="text" name="bahan" id="bahan"  size="30" maxlength="30" /></td>
	<td>Harga Total</td>
    <td>:</td>
    <td><input type="text" name="total" id="total"  size="30" maxlength="30" /></td>
</tr>
<tr>
  <td>Proses</td>
    <td>:</td>
    <td><input type="text" name="proses" id="proses"  size="30" maxlength="30" /></td>
</tr>
<tr>
  
</tr>
<tr>
  
</tr>
<tr>
  
</tr>
<tr>
  
</tr>
<!-- <tr>
  <td>Nama Lengkap</td>
    <td>:</td>
    <td>
    <select name="level" id="level">
        <option value="">-Pilih</option>
        <option value="super admin">Super Admin</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
    </td>
</tr> -->    
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
