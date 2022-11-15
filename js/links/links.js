/**
 * Remove a link set element from link set table
 *
 * @param sClass
 * @param iId
 * @param sAttCode
 * @constructor
 */
function RemoveLinkedSetElementAjax(sClass, iId, sAttCode = null){
	$.post('../pages/ajax.render.php?route=linkset.RemoveRemoteObject', {
		obj_class: sClass,
		obj_key: iId,
		att_code: sAttCode,
		transaction_id: $('#linkset_transactions_id').val()
	}, function (data) {
		if(data.data.success){
			alert('Operation succeeded, todo refresh table !!');
		}
		else{
			alert('Operation failed, todo feedback !!');
		}
	});
}