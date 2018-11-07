	<head>  
        <title>PHP - Sending multiple forms data through jQuery Ajax</title>  
		<link rel="stylesheet" href="<?php echo base_url () ?>assets/css/jquery-ui.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
    </head>
<script type="text/javascript">
$(document).ready(function(){
	$(':input:not([type="submit"])').each(function() {
		$(this).focus(function() {
			$(this).addClass('hilite');
		}).blur(function() {
			$(this).removeClass('hilite');});
	});	
	
	$("#no_po").keyup(function(e){
		var isi = $(e.target).val();
		$(e.target).val(isi.toUpperCase());
		CariAnggota(isi);
	});
	
	$(".angka").keypress(function(data){
		if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) {
			return false;
		}
	});
	
	$("#view").show();
	$("#form").hide();
	
	$("#tambah").click(function(){
		$("#view").hide();
		$("#form").show();
		kosong();
		$("#no_po").focus();
		tampil_data();
		//$("#tampil_data").html('hai...');
	});
	
	function kosong(){
		$(".kosong").val('');
		$(".angka").val('');
		$("#detail_pesanan").val('');
		$("#ukuran").val('');
		$("#jumlah").val('');
		$("#no_rek").val('');
		$("#harga").val('');
		$("#ppn").val('');
		$("#total").val('');
	}
	
	$("#no_po").keyup(function(){
		CariNoPo();
	});
	
	$("#no_po").change(function(){
		CariNoPo();
	});
	
	function CariNoPo(){
		
		var no_po = $("#no_po").val();
		var string = "no_po="+no_po;
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/ref_json/CariNamaClient",
			data	: string,
			cache	: false,
			dataType: "json",
			success	: function(data){
				$("#id_client").val(data.id_client);
				$("#nama_client").val(data.nama_client);
				$("#tgl_po").val(data.tgl_po);
				$("#tgl_pemasangan").val(data.tgl_pemasangan);
			}
		});
	}
	
	function CariNoRek(){
		var no_rek = $("#no_rek").val();
		var string = "no_rek="+no_rek;
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/ref_json/CariNamaRek",
			data	: string,
			cache	: false,
			dataType: "json",
			success	: function(data){
				$("#nama_rek").val(data.nama_rek);
			}
		});
	}
	
	$("#simpan").click(function(){
		var no_po			= $("#no_po").val();
		var tgl_po			= $("#tgl_po").val();
		var tgl_pemasangan	= $("#tgl_pemasangan").val();
		var id_client		= $("#id_client").val();
		var nama_client		= $("#nama_client").val();
		var detail_pesanan	= $("#detail_pesanan").val();
		var ukuran 			= $("#ukuran").val();
		var jumlah 			= $("#jumlah").val();
		var no_rek 			= $("#no_rek").val();
		var harga			= $("#harga").val();
		var ppn				= $("#ppn").val();
		var total			= $("#total").val();
		
		var string = "no_po="+no_po+"&tgl_po="+tgl_po+"&tgl_pemasangan="+tgl_pemasangan+"&id_client="+id_client+"&detail_pesanan="+detail_pesanan+
					 "&ukuran="+ukuran+"&jumlah="+jumlah+"&no_rek="+no_rek+"&harga="+harga+"&ppn="+ppn+"&total="+total;
		
		if(no_po.length==0){
			$.messager.show({
				title:'Info',
				msg:'Maaf, No PO Tidak Boleh Kosong',
				timeout:2000,
				showType:'slide'
			});
			$("#no_po").focus();
			return false;
		}
		if(tgl_po.length==0){
			$.messager.show({
				title:'Info',
				msg:'Maaf, Tanggal PO Tidak Boleh Kosong',
				timeout:2000,
				showType:'slide'
			});
			
			$("#tgl_po").focus();
			return false();
		}
		if(tgl_pemasangan.length==0){
			//alert("Maaf, Nama Rekening tidak boleh kosong");
			$.messager.show({
				title:'Info',
				msg:'Maaf, Tanggal Pemasangan Tidak Boleh Kosong',
				timeout:2000,
				showType:'slide'
			});
			
			$("#tgl_pemasangan").focus();
			return false();
		}
		if(nama_client.length==0){
			$.messager.show({
				title:'Info',
				msg:'Maaf, Nama Client Tidak Boleh Kosong',
				timeout:2000,
				showType:'slide'
			});
			$("#nama_client").focus();
			return false();
		}
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/simpan",
			data	: string,
			cache	: false,
			success	: function(data){
				$.messager.show({
					title:'Info',
					msg:data,
					timeout:2000,
					showType:'slide'
				});
				tampil_data();
			}
		});
		
	});
	
	function tampil_data(){
		//echo "tampil_data";
		var no_po = $("#no_po").val();
		var string = "no_po="+no_po;
		
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/DetailOrder",
			data	: string,
			cache	: false,
			success	: function(data){
				$("#tampil_data").html(data);
			}
		});
	}
	
	$("#tambah_data").click(function(){
		$(".kosong").val('');
		$(".angka").val('');
		$("#nama_client").focus();
		//buat_nojurnal();
	});
	
	$("#kembali").click(function(){
		window.location.assign('<?php echo base_url();?>index.php/penjualan');
	});
});

function editData(id){
	var string = "id="+id;
	//alert(id);
	
	$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/edit",
			data	: string,
			cache	: true,
			dataType : "json",
			success	: function(data){
				
				$("#no_po").val(data.no_po);
				$("#tgl_po").val(data.tgl_po);
				$("#id_client").val(data.id_client);
				$("#nama_client").val(data.nama_client);
				$("#tgl_pemasangan").val(data.tgl_pemasangan);
				//$("#no_rek").focus();
				
				$("#view").hide();
				$("#form").show();
				tampil_data();
			}
	});
	
	function tampil_data(){
		var no_po = $("#no_no").val();
		//var string = "no_jurnal="+no_jurnal;
		
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/DetailOrder",
			data	: string,
			cache	: false,
			success	: function(data){
				$("#tampil_data").html(data);
			}
		});
	}
	
}
//------------AHMAD---------------------

$(document).ready(function(){ 
	
	var count = 0;

	$('#user_dialog').dialog({
		autoOpen:false,
		width:400
	});

	$('#add').click(function(){
		$('#user_dialog').dialog('option', 'title', 'Add Data');
		$('#first_name').val('');
		$('#last_name').val('');
		$('#error_first_name').text('');
		$('#error_last_name').text('');
		$('#first_name').css('border-color', '');
		$('#last_name').css('border-color', '');
		$('#save').text('Save');
		$('#user_dialog').dialog('open');
	});

	$('#save').click(function(){
		var error_first_name = '';
		var error_last_name = '';
		var first_name = '';
		var last_name = '';
		if($('#first_name').val() == '')
		{
			error_first_name = 'First Name is required';
			$('#error_first_name').text(error_first_name);
			$('#first_name').css('border-color', '#cc0000');
			first_name = '';
		}
		else
		{
			error_first_name = '';
			$('#error_first_name').text(error_first_name);
			$('#first_name').css('border-color', '');
			first_name = $('#first_name').val();
		}	
		if($('#last_name').val() == '')
		{
			error_last_name = 'Last Name is required';
			$('#error_last_name').text(error_last_name);
			$('#last_name').css('border-color', '#cc0000');
			last_name = '';
		}
		else
		{
			error_last_name = '';
			$('#error_last_name').text(error_last_name);
			$('#last_name').css('border-color', '');
			last_name = $('#last_name').val();
		}
		if(error_first_name != '' || error_last_name != '')
		{
			return false;
		}
		else
		{
			if($('#save').text() == 'Save')
			{
				count = count + 1;
				output = '<tr id="row_'+count+'">';
				output += '<td>'+first_name+' <input type="hidden" name="hidden_first_name[]" id="first_name'+count+'" class="first_name" value="'+first_name+'" /></td>';
				output += '<td>'+last_name+' <input type="hidden" name="hidden_last_name[]" id="last_name'+count+'" value="'+last_name+'" /></td>';
				output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+count+'">View</button></td>';
				output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+count+'">Remove</button></td>';
				output += '</tr>';
				$('#user_data').append(output);
			}
			else
			{
				var row_id = $('#hidden_row_id').val();
				output = '<td>'+first_name+' <input type="hidden" name="hidden_first_name[]" id="first_name'+row_id+'" class="first_name" value="'+first_name+'" /></td>';
				output += '<td>'+last_name+' <input type="hidden" name="hidden_last_name[]" id="last_name'+row_id+'" value="'+last_name+'" /></td>';
				output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+row_id+'">View</button></td>';
				output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+row_id+'">Remove</button></td>';
				$('#row_'+row_id+'').html(output);
			}

			$('#user_dialog').dialog('close');
		}
	});

	$(document).on('click', '.view_details', function(){
		var row_id = $(this).attr("id");
		var first_name = $('#first_name'+row_id+'').val();
		var last_name = $('#last_name'+row_id+'').val();
		$('#first_name').val(first_name);
		$('#last_name').val(last_name);
		$('#save').text('Edit');
		$('#hidden_row_id').val(row_id);
		$('#user_dialog').dialog('option', 'title', 'Edit Data');
		$('#user_dialog').dialog('open');
	});

	$(document).on('click', '.remove_details', function(){
		var row_id = $(this).attr("id");
		if(confirm("Are you sure you want to remove this row data?"))
		{
			$('#row_'+row_id+'').remove();
		}
		else
		{
			return false;
		}
	});

	$('#action_alert').dialog({
		autoOpen:false
	});

	$('#user_form').on('submit', function(event){
		event.preventDefault();
		var count_data = 0;
		$('.first_name').each(function(){
			count_data = count_data + 1;
		});
		if(count_data > 0)
		{
			var form_data = $(this).serialize();
			$.ajax({
				url:"insert.php",
				method:"POST",
				data:form_data,
				success:function(data)
				{
					$('#user_data').find("tr:gt(0)").remove();
					$('#action_alert').html('<p>Data Inserted Successfully</p>');
					$('#action_alert').dialog('open');
				}
			})
		}
		else
		{
			$('#action_alert').html('<p>Please Add atleast one data</p>');
			$('#action_alert').dialog('open');
		}
	});
	
});

//------------END-----------------------
</script> 
	
<div id="view">
<div style="float:left; padding-bottom:5px;">
<button type="button" name="tambah" id="tambah" class="easyui-linkbutton" data-options="iconCls:'icon-add'">Tambah Data</button>

<a href="<?php echo base_url();?>index.php/penjualan">
<button type="button" name="refresh" id="refresh" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">Refresh</button>
</a>
</div>
<div style="float:right; padding-bottom:5px;">
<form name="form" method="post" action="<?php echo base_url();?>index.php/penjualan">
Cari No.Jurnal/No.Rek : <input type="text" name="txt_cari" id="txt_cari" size="50" />
<button type="submit" name="cari" id="cari" class="easyui-linkbutton" data-options="iconCls:'icon-search'">Cari</button>
</form>
</div>
<div style="padding:10px;"></div>
<table id="dataTable" width="100%">
<tr>
	<th>No</th>
    <th>No PO</th>
    <th>Tanggal PO</th>
    <th>Tanggal Pemasangan</th>
	<th>Nama Client</th>
	<th>Total Nilai</th>
    <th>Opsi</th>
</tr>
<?php
	if($data->num_rows()>0){
		$jml_dr=0;
		$jml_kr=0;
		$no =1+$hal;
		foreach($data->result_array() as $db){  
		$tgl_po = $this->app_model->tgl_sql($db['tgl_po']);
		$tgl_pasang = $this->app_model->tgl_sql($db['tgl_pemasangan']);
		$nama_client = $this->app_model->ClientPenjualan($db['id_client']);
		?>    
    	<tr>
			<td align="center" width="20"><?php echo $no; ?></td>
            <td align="center" width="100" ><?php echo $db['no_po']; ?></td>
            <td align="center" width="100"><?php echo $tgl_po; ?></td>
			<td align="center" width="100"><?php echo $tgl_pasang; ?></td>
            <td align="center" ><?php echo $nama_client; ?></td>
			<td align="center" ><?php echo $db['total']; ?></td>
            
			
            <td align="center" width="80">
            <?php echo "<a href='javascript:editData(\"{$db['no_po']}\")'>";?>
			<img src="<?php echo base_url();?>asset/images/ed.png" title='Edit'>
			</a>
            <a href="<?php echo base_url();?>index.php/penjualan/hapus/<?php echo $db['no_po'];?>"
            onClick="return confirm('Anda yakin ingin menghapus nomor PO ini?')">
			<img src="<?php echo base_url();?>asset/images/del.png" title='Hapus'>
			</a>
            </td>
    </tr>
    <?php
		$jml_dr = $jml_dr+$db['harga'];
		$jml_kr = $jml_kr+$db['ppn'];
		$no++;
		}
	}else{
		$jml_dr = 0;
		$jml_kr = 0;
	?>
    	<tr>
        	<td colspan="9" align="center" >Tidak Ada Data</td>
        </tr>
    <?php	
	}
?>
<!-- <tr>
	<td align="right" colspan="6"><b>Jumlah</b></td>
    <td align="right"><b><?php echo number_format($jml_dr);?></b></td>
    <td align="right"><b><?php echo number_format($jml_kr);?></b></td>    
</tr>  -->      
</table>
<?php echo "<table align='center'><tr><td>".$paginator."</td></tr></table>"; ?>
</div>
<div id="form">
<fieldset>
<table width="100%">
<tr>
	<td width="50%" valign="top">
        <table width="100%">
        <tr>
            <td width="20%">No PO</td>
            <td width="5">:</td>
            <td>
				<select name="no_po" id="no_po" class="kosong">
				<option value="">-PILIH-</option>
				<?php
				foreach($list_po->result_array() as $t){
				?>
				<option value="<?php echo $t['no_po'];?>"><?php echo $t['no_po'];?></option>
				<?php } ?>
				</select>
            </td>
			<td><input type="text" name="id_client" id="id_client" class="kosong" size="20" maxlength="12" readonly="readonly" hidden="hidden" /></td>
			 
        </tr>
		<tr>
		<td width="20%">Nama Client</td>
            <td width="5">:</td>
		<td><input type="text" name="nama_client" id="nama_client" class="kosong" size="50" maxlength="50" readonly="readonly" /></td>
		</tr>
        <!-- <tr>    
            <td>Nama Client</td>
            <td>:</td>
            <td><input type="text" name="nama_client" id="nama_client" class="kosong" size="50" maxlength="50" readonly="readonly" /></td>            
        </tr>-->
        </table>
	</td>
    <td width="50%" valign="top">
        <table width="100%">
        <tr>
            <td width="20%">Tanggal PO</td>
            <td width="5">:</td>
			<td><input type="text" name="tgl_po" id="tgl_po" class="kosong" size="30" maxlength="30" readonly="readonly" /></td>
        </tr>
        <tr>    
            <td width="20%">Tanggal Pemasangan</td>
            <td width="5">:</td>
			<td><input type="text" name="tgl_pemasangan" id="tgl_pemasangan" class="kosong" size="30" maxlength="30" readonly="readonly" /></td>
        </tr>
        </table>
	</td>
</tr>
</table>            
</fieldset>
<div style="margin:5px;"></div>
<fieldset class="atas">
<div class="container">
			
			
			<div align="right" style="margin-bottom:5px;">
				<button type="button" name="add" id="add" class="btn btn-success btn-xs">Add</button>
			</div>
			<br />
			<form method="post" id="user_form">
				<div class="table-responsive">
					<table class="table table-striped table-bordered" id="user_data">
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Details</th>
							<th>Remove</th>
						</tr>
					</table>
				</div>
				<div align="center">
					<input type="submit" name="insert" id="insert" class="btn btn-primary" value="Insert" />
				</div>
			</form>

			<br />
		</div>
		<div id="user_dialog" title="Add Data">
			<div class="form-group">
				<label>Enter First Name</label>
				<input type="text" name="first_name" id="first_name" class="form-control" />
				<span id="error_first_name" class="text-danger"></span>
			</div>
			<div class="form-group">
				<label>Enter Last Name</label>
				<input type="text" name="last_name" id="last_name" class="form-control" />
				<span id="error_last_name" class="text-danger"></span>
			</div>
			<div class="form-group" align="center">
				<input type="hidden" name="row_id" id="hidden_row_id" />
				<button type="button" name="save" id="save" class="btn btn-info">Save</button>
			</div>
		</div>
		<div id="action_alert" title="Action">

		</div>
<fieldset class="bawah">
<table width="100%">
<tr>
	
</tr>
</table>  
</fieldset>   
</div>