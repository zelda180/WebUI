<?php 
include ("header.php");
include ("pass.php");
?>

<div class="panel panel-default">
    <table class="table-hover table-condensed table-bordered table">
        <form action="sendtoaddress.php" method="POST"><input type="hidden">
            <input type="hidden" name="a">
                <tr>
                    <td><b>To Address</b></td>
                    <td><input class="form-control" type="text" name="address" value="<?php print($address); ?>"></td>
                </tr>
                <tr>
                    <td><b>Amount</b></td>
                    <td><input class="form-control" type="text" name="amount" value="<?php print($amount); ?>"></td>   
                </tr>
                <tr>
                    <td><b>Comment</b></td>
                    <td><input class="form-control" type="text" name="comment" value="<?php print($comment); ?>"></td>   
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><button class='btn btn-default' type="submit" value="Preview"><b>Preview</b></button></td>
        </form>
    </table>
</div>
</div>
<?php include ("footer.php"); ?>