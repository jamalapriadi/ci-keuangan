<html>
<head><title>Ajax Combo</title>
<script lang="javascript" src="<?php echo base_url()?>js/jquery-1.6.2.js"></script>
                              <script lang="javascript">
$(document).ready(function() {

    $("#cboProv").change(function() {
        $("#cboKota").empty();
        $.post('sample_form/ambil_kota',
        { id_prov : $("#cboProv option:selected").val() },
        function(data) {
            $.each(data, function(i, item) {
                $("#cboKota").append(
                    '<option value="' + item.id_kota + '">'+item.nama_kota + '</option>'
                );
            })
        },
        "json"
              );
    });
});
</script>
</head>
<body>
<form>
<select id="cboProv">
<option value='1'>Jabar</option>
<option value='2'>Jateng</option>
</select>
<select id="cboKota">
</select>
</form>
</body>
</html>
