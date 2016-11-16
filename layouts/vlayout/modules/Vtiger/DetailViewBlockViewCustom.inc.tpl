<!--INVOCADO DESDE DetailViewBlockView.tpl, requiere variable custom pra indicar que codigo incluir -->

<!--jmangarret nov2016 link para nro de ticket -->
{if $custom eq 1}
	{if $FIELD_MODEL->getName() eq 'nrodeticket'}
	<span class="value" data-field-type="{$FIELD_MODEL->getFieldDataType()}">						
		<a target="_blank" href="modules/Vtiger/actions/redirectTicket.php?number={$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}">
	    {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
		</a>
	 </span>
	{else}
	<!--span original Vtiger -->
	 <span class="value" data-field-type="{$FIELD_MODEL->getFieldDataType()}">
	    {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
	 </span>
	{/if}
{/if}