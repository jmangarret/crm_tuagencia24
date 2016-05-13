{*<!--

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

-->*}

{*<!-- module header -->*}
<script language="JavaScript" type="text/javascript">
var bCheckRelatedFieldChanges = false;
var bHidePopups = '{$CONFIG.hidepopup}';
</script>

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<!-- overriding the pre-defined #company to avoid clash with vtiger_field in the view -->
{literal}
<style type='text/css'>
#company {
	height: auto;
	width: 90%;
}
</style>
{/literal}

<script type="text/javascript">
var gVTModule = '{$smarty.request.module|@vtlib_purify}';
function sensex_info()
{ldelim}
        var Ticker = $('tickersymbol').value;
        if(Ticker!='')
        {ldelim}
                $("vtbusy_info").style.display="inline";
                new Ajax.Request(
                      'index.php',
                      {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                                method: 'post',
                                postBody: 'module={$MODULE}&action=Tickerdetail&tickersymbol='+Ticker,
                                onComplete: function(response) {ldelim}
                                        $('autocom').innerHTML = response.responseText;
                                        $('autocom').style.display="block";
                                        $("vtbusy_info").style.display="none";
                                {rdelim}
                        {rdelim}
                );
        {rdelim}
{rdelim}
function AddressSync(Addform,id)
{ldelim}
	checkAddress(Addform,id);
{rdelim}

</script>

		{include file='Buttons_List1.tpl'}

{*<!-- Contents -->*}
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
   <tr>
	<td valign=top><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>

	<td class="showPanelBg" valign=top width=100%>
		{*<!-- PUBLIC CONTENTS STARTS-->*}
		<div class="small" style="padding:20px">
			{* vtlib customization: use translated label if available *}
			{assign var="SINGLE_MOD_LABEL" value=$SINGLE_MOD}
			{if $APP.$SINGLE_MOD} {assign var="SINGLE_MOD_LABEL" value=$APP.SINGLE_MOD} {/if}

			{if $OP_MODE eq 'edit_view'}
				{assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
		  		{if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}
				<span class="lvtHeaderText"><font color="purple">[ {$USE_ID_VALUE} ] </font>{$NAME} - {$APP.LBL_EDITING} {$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span> <br>
				{$UPDATEINFO}
			{/if}
			{if $OP_MODE eq 'create_view'}
				<span class="lvtHeaderText">{$APP.LBL_CREATING} {$SINGLE_MOD|@getTranslatedString:$MODULE}</span> <br>
			{/if}

			<hr noshade size=1>
			<br>

			{include file='EditViewHidden.tpl'}

			{*<!-- Account details tabs -->*}
			<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
			   <tr>
				<td>
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
					   <tr>
						<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
						<td class="dvtSelectedCell" align=center nowrap> {$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</td>
						<td class="dvtTabCache" style="width:10px">&nbsp;</td>
						<td class="dvtTabCache" style="width:100%">&nbsp;</td>
					   </tr>
					</table>
				</td>
			   </tr>
			   <tr>
				<td valign=top align=left >
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
					   <tr>

						<td align=left>
							{*<!-- content cache -->*}

							<table border=0 cellspacing=0 cellpadding=0 width=100%>
							   <tr>
								<td id ="autocom"></td>
							   </tr>
							   <tr>
								<td style="padding:10px">
									<!-- General details -->
									<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
									   <tr>
										<td  colspan=4 style="padding:5px">
											<div align="center">
												{if $MODULE eq 'Webmails'}
													<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.module.value='Webmails';this.form.send_mail.value='true';this.form.record.value='{$ID}'" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
											{elseif $MODULE eq 'Accounts'}
											<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; displaydeleted();  if(formValidate()) {ldelim} if(AjaxDuplicateValidate('Accounts','accountname',this.form)) {ldelim} AddressSync(this.form,{$ID}); {rdelim} {rdelim}"  type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
												{else}
													<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmButton small save" onclick="this.form.action.value='Save'; displaydeleted(); return checkPayments(); " type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
												{/if}
													<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
											</div>
										</td>
									   </tr>

									   <!-- included to handle the edit fields based on ui types -->
									   {foreach key=header item=data from=$BLOCKS}
{if $header eq $MOD.LBL_ADDITIONAL_INFO}
								   </tbody>
								   </table>
								   {if empty($dateFormat)}
										{assign var="dateFormat" value=$APP.NTC_DATE_FORMAT|@parse_calendardate}
									{/if}

										<script type="text/javascript">
											var LBL_PAID = "Paid";
											var LBL_PENDING = "Pending";
											var LBL_PAID_TRANSACTION = "{$MOD.LBL_PAID_TRANSACTION}";
											var LBL_PENDING_TRANSACTION = "{$MOD.LBL_PENDING_TRANSACTION}";
											var LBL_AUTO_FILL = "{$MOD.LBL_AUTO_FILL}";
											var LBL_AUTO_FILL_ASSOC = "{$MOD.LBL_AUTO_FILL_ASSOC}";
											var LBL_PAID_ALL_PAYMENTS = "{$MOD.LBL_PAID_ALL_PAYMENTS}";

											document.getElementById("accountingpaidamount").setAttribute('readonly',true);
											document.getElementById("accountingpaidamount").value={$PAID};
											document.getElementById("accountingpaidoustanding").setAttribute('readonly',true);
											document.getElementById("accountingpaidoustanding").value={$OUSTANDING};
											var paymentId = 0;
											var decimalSeparator = "{$CONFIG.decimalseparator}";
											document.getElementById('accountingamount').onkeypress=function() {ldelim}
														onKeypressAmountField(document.getElementById('accountingamount'), event);
													{rdelim};

											document.getElementById('accountingamount').onkeyup=function() {ldelim}
														calculatePaidAndBalance();
													{rdelim};

											var szLastRelatedFieldChange = document.getElementById("accountingrelated2_display").value;
											var szLastRelatedAssoc = new Array();
											addCurrencies('{$CURRENCIES}', '{$RECORD_CURRENCY}');
											document.getElementsByName('accountingstate')[0].onchange=function() {ldelim}
												setPaymentsToPaid();
											{rdelim};
										</script>

										{if $CONFIG.showvat eq 'true'}
											{assign var="displayVAT" value=""}
										{else}
											{assign var="displayVAT" value="none"}
										{/if}
									<div id="payments_table_div" name="payments_table_div">
								   <table id="payments_table" CELLSPACING=10>
								   		<tr>
											<th>#</th>
											<th>{$MOD.LBL_PAYMENT_REF}</th>
											<th>{$MOD.LBL_TRANSACTION_METHOD}</th>
											{*<th>{$MOD.LBL_PARTIAL_NET_AMOUNT}</th>*}
											<th>{$MOD.LBL_PARTIAL_AMOUNT}</th>
											<th style="display: {$displayVAT}">{$MOD.LBL_PARTIAL_TAX}</th>
											<th>{$MOD.LBL_TRANSACTION_DUE_DATE}</th>
											<th></th>
											<th>{$MOD.LBL_TRANSACTION_DATE}</th>
											<th></th>
											{if $CONFIG.associnvoice eq "true"}
												<th>&nbsp&nbsp&nbsp&nbsp</th>
											{/if}
											<th>{$MOD.LBL_PARTIAL_PAID_AMOUNT}</th>
											<th>
												<input type="hidden" name="nPayments" id="nPayments" value="0" />
												<input type="hidden" name="nPaymentsInc" id="nPaymentsInc" value="0" />
											</th>
											<th></th>
										</tr>
									   		{foreach from=$PAYMENTS item=PAYMENT name=foo}
											   		<tr>

											   		{assign var="datevalue" value=$PAYMENT.date}
											   		{*	{if $PAYMENT.paid eq "1"}
											   				{assign var="datevalue" value=$PAYMENT.date}
											   			{else}
											   				{assign var="datevalue" value=""}
											   			{/if}
														*}
											   			<td class="nPayment" name="nPayment"><font size="+1">{$smarty.foreach.foo.index+1}</font></td>
											   			<td><input size="10" class="paymentref" name="paymentref_{$smarty.foreach.foo.index}" id="paymentref_{$smarty.foreach.foo.index}" value="{$PAYMENT.ref}" /></td>
											   			<td>
											   				<select class="paymentmethod" name="paymentmethod_{$smarty.foreach.foo.index}" id="paymentmethod_{$smarty.foreach.foo.index}">
											   					{foreach from=$TRANSACTION_METHOD item=v}
											   						{if $PAYMENT.method eq $v}
											   							{assign var="selectedMethod" value="true"}
											   							<option selected>{$v}</option>
											   						{else}
											   							<option>{$v}</option>
											   						{/if}
											   					{/foreach}
											   					{if $selectedMethod neq "true"}
											   						<option selected>{$PAYMENT.method}</option>
											   					{/if}
															</select>
														</td>

														<td><input size="10" class="paymentamount" name="paymentamount_{$smarty.foreach.foo.index}" id="paymentamount_{$smarty.foreach.foo.index}" value="{$PAYMENT.amount}" onkeypress="onKeypressAmountField(this, event);" onKeyup="calculatePaidAndBalance();" /></td>

														<td style="display: {$displayVAT}"><input size="4" class="paymenttax" name="paymenttax_{$smarty.foreach.foo.index}" id="paymenttax_{$smarty.foreach.foo.index}" value="{$PAYMENT.tax}" /></td>
														<td><input size="8" onchange="calculatePaidAndBalance();" class="paymentduedate" id="jscal_field_paymentduedate_{$smarty.foreach.foo.index}" name="paymentduedate_{$smarty.foreach.foo.index}" value="{$PAYMENT.paymentduedate}" readonly="readonly" type="text" style="border:1px solid #bababa;"></td>
														<td>
															<img src="modules/Accounting/images/btnL3Calendar.gif" id="jscal_trigger_paymentduedate_{$smarty.foreach.foo.index}">
															<input type="image" src="themes/images/clear_field.gif" alt="Clear" title="Clear" language="javascript" onclick="document.getElementById('jscal_field_paymentduedate_{$smarty.foreach.foo.index}').value='';return false;">
														</td>
											   			<td><input size="8" onchange="calculatePaidAndBalance();" class="paymentdate" id="jscal_field_paymentdate_{$smarty.foreach.foo.index}" name="paymentdate_{$smarty.foreach.foo.index}" value="{$datevalue}" readonly="readonly" type="text" style="border:1px solid #bababa;"></td>
											   			<td>
											   				<img src="modules/Accounting/images/btnL3Calendar.gif" id="jscal_trigger_paymentdate_{$smarty.foreach.foo.index}">
											   				<input type="image" src="themes/images/clear_field.gif" alt="Clear" title="Clear" language="javascript" onclick="document.getElementById('jscal_field_paymentdate_{$smarty.foreach.foo.index}').value='';return false;">
											   			</td>

											   			{if $CONFIG.associnvoice eq "true"}
															<td>
																{if $PAYMENT.assoc_display eq ""}
																	<a class="assoc_link" id="paymentassoc_link_{$smarty.foreach.foo.index}" href="javascript:void(0)" onclick="javascript: onAssocInvoice({$smarty.foreach.foo.index}, 'Income');">{$MOD.LBL_PARTIAL_INV_ASSOC}</a>
																	<div class="assoc_div" id="paymentassoc_div_{$smarty.foreach.foo.index}" style="display: none">
																{else}
																	<a class="assoc_link" id="paymentassoc_link_{$smarty.foreach.foo.index}" href="javascript:void(0)" onclick="javascript: onAssocInvoice({$smarty.foreach.foo.index}, 'Income');" style="display: none">{$MOD.LBL_PARTIAL_INV_ASSOC}</a>
																	<div class="assoc_div" id="paymentassoc_div_{$smarty.foreach.foo.index}">
																{/if}
																		<input class="assoc_mod" id="paymentassoc_{$smarty.foreach.foo.index}_mod" name="paymentassoc_{$smarty.foreach.foo.index}_mod" type="hidden" value="{$PAYMENT.assoc_mod}">
																		<input class="assoc_id" id="paymentassoc_{$smarty.foreach.foo.index}" name="paymentassoc_{$smarty.foreach.foo.index}" type="hidden" value="{$PAYMENT.associnv}">
																		<input class="assoc_display" id="paymentassoc_{$smarty.foreach.foo.index}_display" name="paymentassoc_{$smarty.foreach.foo.index}_display" readonly="" type="text" style="border:1px solid #bababa;" value="{$PAYMENT.assoc_display}">
																		<input type="image" src="themes/images/clear_field.gif" alt="Clear" title="Clear" language="javascript" onclick="document.getElementById('paymentassoc_div_{$smarty.foreach.foo.index}').style.display='none';document.getElementById('paymentassoc_link_{$smarty.foreach.foo.index}').style.display='';document.getElementById('paymentassoc_{$smarty.foreach.foo.index}_display').value='';document.getElementById('paymentassoc_{$smarty.foreach.foo.index}').value='';return false;">
																	</div>
															</td>
														{/if}
														{if $PAYMENT.paid eq "1"}
											   				{assign var="checked" value="checked"}
											   			{else}
											   				{assign var="checked" value=""}
											   			{/if}
											   			<td><input type="checkbox" {$checked} class="partial_paid" name="partial_paid_{$smarty.foreach.foo.index}" id="partial_paid_{$smarty.foreach.foo.index}" onClick="calculatePaidAndBalance();"><td>
											   			<td>
											   				{if $smarty.foreach.foo.first neq TRUE}
											   					<img width="16" height="16" src="modules/Accounting/images/delete.png" onclick="onDeletePayment(this);">
											   				{/if}
											   			</td>

											   			<script type="text/javascript" name="massedit_calendar_paymentdate" id='massedit_calendar_paymentdate_{$smarty.foreach.foo.index}'>
															Calendar.setup ({ldelim}
																inputField : "jscal_field_paymentdate_{$smarty.foreach.foo.index}", ifFormat : "{$dateFormat}", showsTime : false, button : "jscal_trigger_paymentdate_{$smarty.foreach.foo.index}", singleClick : true, step : 1
															{rdelim});

															var dateFormat = "{$dateFormat}";
															document.getElementById("nPayments").value++;

															Calendar.setup ({ldelim}
																inputField : "jscal_field_paymentduedate_{$smarty.foreach.foo.index}", ifFormat : "{$dateFormat}", showsTime : false, button : "jscal_trigger_paymentduedate_{$smarty.foreach.foo.index}", singleClick : true, step : 1
															{rdelim});

															var dateFormat = "{$dateFormat}";
														</script>
														<td></td>
											   		</tr>

											   		<script type="text/javascript">
											   			{if $CONFIG.associnvoice eq "true"}
											   				szLastRelatedAssoc["paymentassoc_{$smarty.foreach.foo.index}_display"] = document.getElementById("paymentassoc_{$smarty.foreach.foo.index}_display").value;
											   			{/if}
											   		</script>

										   	{/foreach}

										   	<br />
										   	<tr>
										   		<td></td><td></td>
												<td align="right">{$MOD.LBL_SUM_OF_PAYMENTS}:</td>
												{*<td></td>*}
												<td><strong><span id="summation"></span></strong>&nbsp&nbsp<img id="summation_image" style="display: none" width="12px" src="modules/Accounting/images/check.png" /></td>
												<td style="display: {$displayVAT}"></td>
												<td></td><td></td><td></td><td></td>
												{if $CONFIG.associnvoice eq "true"}
													<td></td>
												{/if}
												<td></td><td></td><td></td>
										   	</tr>
										   	<tr>
										   		<td></td><td></td>
												<td style="display: " id="summationremainingspan" align="right">{$MOD.LBL_SUM_OF_PAYMENTS_REMAINING}:</td>
												{*<td></td>*}
												<td><strong><span id="summationremaining"></span></strong></td>
												<td style="display: {$displayVAT}"></td>
												<td></td><td></td><td></td><td></td>
												{if $CONFIG.associnvoice eq "true"}
													<td></td>
												{/if}
												<td></td><td></td><td></td>
										   	</tr>

										   	<script type="text/javascript">
										   		calculatePaidAndBalance();
										   	</script>
								   	</table>
								   	</div>
								   	<br />
								   	<table align="right" CELLSPACING=5>
								   		<tr>
								   			<td bgcolor="#D7FFD7" width="12px" style="border: 1px; border-style: solid;border-color: grey"></td><td>{$MOD.LBL_PAID_STATE_COMBOBOX}</td>
								   			<td bgcolor="#FF9D9D" width="12px" style="border: 1px; border-style: solid;border-color: grey"><td>{$MOD.LBL_OUT_OF_DATE}</td>
								   			<td bgcolor="#FFFFFF" width="12px" style="border: 1px; border-style: solid;border-color: grey"><td>{$MOD.LBL_PENDING_STATE_COMBOBOX}</td>
								   		</tr>
								   	</table>
								  {* 	</table> *}
								   	<br />
								   	<tr><td></td></tr>
								   	<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
								   		<tbody>

								   {/if}


							<!-- This is added to display the existing comments -->
							{if $header eq $MOD.LBL_COMMENTS || $header eq $MOD.LBL_COMMENT_INFORMATION}
							   <tr><td>&nbsp;</td></tr>
							   <tr>
								<td colspan=4 class="dvInnerHeader">
							        	<b>{$MOD.LBL_COMMENT_INFORMATION}</b>
								</td>
							   </tr>
							   <tr>
								<td colspan=4 class="dvtCellInfo">{$COMMENT_BLOCK}</td>
							   </tr>
							   <tr><td>&nbsp;</td></tr>
							{/if}



									      <tr>
										{if $header== $MOD.LBL_ADDRESS_INFORMATION && ($MODULE == 'Accounts' || $MODULE == 'Quotes' || $MODULE == 'PurchaseOrder' || $MODULE == 'SalesOrder'|| $MODULE == 'Invoice')}
                                                                                <td colspan=2 class="detailedViewHeader">
                                                                                <b>{$header}</b></td>
                                                                                <td class="detailedViewHeader">
                                                                                <input name="cpy" onclick="return copyAddressLeft(EditView)" type="radio"><b>{$APP.LBL_RCPY_ADDRESS}</b></td>
                                                                                <td class="detailedViewHeader">
                                                                                <input name="cpy" onclick="return copyAddressRight(EditView)" type="radio"><b>{$APP.LBL_LCPY_ADDRESS}</b></td>
										{elseif $header== $MOD.LBL_ADDRESS_INFORMATION && $MODULE == 'Contacts'}
										<td colspan=2 class="detailedViewHeader">
                                                                                <b>{$header}</b></td>
                                                                                <td class="detailedViewHeader">
                                                                                <input name="cpy" onclick="return copyAddressLeft(EditView)" type="radio"><b>{$APP.LBL_CPY_OTHER_ADDRESS}</b></td>
                                                                                <td class="detailedViewHeader">
                                                                                <input name="cpy" onclick="return copyAddressRight(EditView)" type="radio"><b>{$APP.LBL_CPY_MAILING_ADDRESS}</b></td>
                                                                                {else}
										<td colspan=4 class="detailedViewHeader">
											<b>{$header}</b>
											{if $header eq $MOD.LBL_PAYMENTS_INFO}
                       	        				<a href="javascript:void(0)" onclick="onAddPaymentButton_Click(document.getElementById('addMultiplePaymentImg'));"><img style="border: 0px; padding-left: 10px" align="right" id="addMultiplePaymentImg" src="modules/Accounting/images/add_multiple.png" style="cursor: pointer" title="{$MOD.LBL_ADD_MULTIPLE_PAYMENT}" ></a>
                       	        				<a href="javascript:void(0)" onclick="onAddPayment();"><img style="border: 0px; padding-left: 5px" align="right" id="addPaymentImg" src="modules/Accounting/images/add.png" style="cursor: pointer" title="{$MOD.LBL_ADD_PAYMENT}"></a>
                       	        			{/if}
										{/if}
										</td>
									      </tr>

										<!-- Handle the ui types display -->
										{include file="modules/Accounting/DisplayFields.tpl"}

									   {/foreach}


									   <!-- Added to display the Product Details in Inventory-->
									   {if $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Invoice'}
							   		   <tr>
										<td colspan=4>
											{include file="ProductDetailsEditView.tpl"}
										</td>
							   		   </tr>
									   {/if}

									   <tr>
										<td  colspan=4 style="padding:5px">
											<div align="center">
										{if $MODULE eq 'Emails'}
										<input title="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_KEY}" class="crmbutton small create" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid={$ENTITY_ID}&entity={$ENTITY_TYPE}','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL}">
										<input title="{$MOD.LBL_SEND}" accessKey="{$MOD.LBL_SEND}" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  {$MOD.LBL_SEND}  " >
										{/if}
										{if $MODULE eq 'Webmails'}
										<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.module.value='Webmails';this.form.send_mail.value='true';this.form.record.value='{$ID}'" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{elseif $MODULE eq 'Accounts'}
                                		                     <input type='hidden'  name='address_change' value='no'>
                                                                             <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; displaydeleted();  if(formValidate()) {ldelim} if(AjaxDuplicateValidate('Accounts','accountname',this.form)) {ldelim} AddressSync(this.form,{$ID}); {rdelim} {rdelim}" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{else}
              				                                	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  displaydeleted();return checkPayments();" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{/if}
                                               					<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
											</div>
										</td>
									   </tr>
									</table>
								</td>
							   </tr>
							</table>
						</td>
					   </tr>
					</table>
				</td>
			   </tr>
			</table>
		<div>
	</td>
	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@vtiger_imageurl:$THEME}"></td>
   </tr>
</table>
<!--added to fix 4600-->
<input name='search_url' id="search_url" type='hidden' value='{$SEARCH}'>
</form>


{if ($MODULE eq 'Emails' || 'Documents') and ($USE_RTE eq 'true')}
	<script type="text/javascript" src="include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" defer="1">
	var textAreaName = null;
	{if $MODULE eq 'Documents'}
		textAreaName = "notecontent";
	{else}
		textAreaName = 'description';
	{/if}

	<!-- Solution for ticket #6756-->
	CKEDITOR.replace( textAreaName,
	{ldelim}
		extraPlugins : 'uicolor',
		uiColor: '#dfdff1',
			on : {ldelim}
				instanceReady : function( ev ) {ldelim}
					 this.dataProcessor.writer.setRules( 'p',  {ldelim}
						indent : false,
						breakBeforeOpen : false,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : false
				{rdelim});
			{rdelim}
		{rdelim}
	{rdelim});
	var oCKeditor = CKEDITOR.instances[textAreaName];
</script>
{/if}

{if $MODULE eq 'Accounts'}
<script>
	ScrollEffect.limit = 201;
	ScrollEffect.closelimit= 200;
</script>
{/if}
<script>

        var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})

        var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})

        var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})

	var ProductImages=new Array();
	var count=0;

	function delRowEmt(imagename)
	{ldelim}
		ProductImages[count++]=imagename;
	{rdelim}

	function displaydeleted()
	{ldelim}
		var imagelists='';
		for(var x = 0; x < ProductImages.length; x++)
		{ldelim}
			imagelists+=ProductImages[x]+'###';
		{rdelim}

		if(imagelists != '')
			document.EditView.imagelist.value=imagelists
	{rdelim}

</script>

<!-- vtlib customization: Help information assocaited with the fields -->
{if $FIELDHELPINFO}
<script type='text/javascript'>
{literal}var fieldhelpinfo = {}; {/literal}
{foreach item=FIELDHELPVAL key=FIELDHELPKEY from=$FIELDHELPINFO}
	fieldhelpinfo["{$FIELDHELPKEY}"] = "{$FIELDHELPVAL}";
{/foreach}
</script>
{/if}
<!-- END -->
