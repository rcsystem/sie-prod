/*
* MODULO -> REQUISICIONES 
* Autor: Horus Samael Rivas Pedraza
* Email: horus.riv.ped@gmail.com
* Telefono: 56 2439 2632
*/
$("#modal").hide();
$("#btn_buscar").on("click", function (e) {
    $("#modal").hide();
    e.preventDefault();
    if ($('#nomina').val().length == 0) {
        $('#nomina').addClass('has-error');
        $("#error_nomina").text('Campo Requerido');
        return false;
    }
    $("#btn_buscar").prop("disabled", true);
    $("#error_nomina").text('');
    $('#nomina').removeClass('has-error');
    let payroll = new FormData();
    payroll.append('nomina', $("#nomina").val());
    $.ajax({
        data: payroll,
        url: `${urls}requisiciones/datos_personal`,
        type: "post",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentTypeasignacion
        dataType: "json",
        success: function (resp) {
            $("#btn_buscar").prop("disabled", false);
            if (resp == false) {
                $('#nomina').addClass('has-error');
                $("#error_nomina").text('Número de Nomina no Encontrado');
            } else {
                $("#registro_modal").val(resp.id_notifica);
                $("#nombre_modal").val(resp.name);
                $("#apellido_p_modal").val(resp.surname);
                $("#apellido_m_modal").val(resp.second_surname);
                $("#id_manager").val(resp.id_user_notificar);
                $("#modal").show();
            }
        }
    });
});

$("#id_manager").on("change", function (e) {
    $("#id_manager").removeClass('has-error');
    $("#error_id_manager").text('');
});
$("#nomina").on("change", function (e) {
    $("#nomina").removeClass('has-error');
    $("#error_nomina").text('');
});

$("#editManager").submit(function (e) {
    e.preventDefault();
    if ($("#id_manager").val() == "") {
        $("#id_manager").addClass('has-error');
        $("#error_id_manager").text('Campo Requerido');
        return false;
    }
    $("#actualiza_gerente").prop("disabled", true);
    let manger = new FormData($("#editManager")[0]);
    $.ajax({
        data: manger,
        url: `${urls}requisiciones/editar_datos_personal`,
        type: "post",
        processData: false, // dile a jQuery que no procese los datos
        contentType: false, // dile a jQuery que no establezca contentTypeasignacion
        dataType: "json",
        success: function (resp) {
            console.log(resp);
            $("#actualiza_gerente").prop("disabled", false);
            if (resp == true) {
                $("#nomina").val('');
                $("#registro_modal").val('');
                $("#nombre_modal").val('');
                $("#apellido_p_modal").val('');
                $("#apellido_m_modal").val('');
                $("#id_manager").val('');
                $("#modal").hide();
                Swal.fire("!Los datos se han Actualizado!", "", "success");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Algo salió Mal! Contactar con el Administrador del Sistema",
                });
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error: ​​[ ${jqXHR.status} ]`,
        });
    });

})