$(function() {
$('#auction').change(function(){
    if (this.checked) {
        $('#auctionreg').show();
    }
    else
    {
    	$('#auctionreg').hide();
    }
});
});