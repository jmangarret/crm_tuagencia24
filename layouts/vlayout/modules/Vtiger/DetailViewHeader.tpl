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
{strip}
	{assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}
	<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
	<div class="detailViewContainer">
		<div class="row-fluid detailViewTitle">
			<div class="{if $NO_PAGINATION} span12 {else} span10 {/if}">
				<div class="row-fluid">
					<div class="span5">
						<div class="row-fluid">
							{include file="DetailViewHeaderTitle.tpl"|vtemplate_path:$MODULE}
						</div>
					</div>

					<div class="span7">
						<div class="pull-right detailViewButtoncontainer">
							<div class="btn-toolbar">
							<!-- jmangarret BOTON DE ACCION PROCESAR EN VISTA DETALLE DEL LOCALIZADOR, may2016 !-->				   
							{if $MODULE eq 'Localizadores'}
							<span class="btn-group">
									<a href="javascript:void(0);">
									<button id="{$MODULE}_detail_basicAction_Process" class="btn addButton">
										<i class="icon-plus icon-white"></i>&nbsp;
										<strong>Procesar</strong> 
									</button>
									</a>
							</span>
							<script type="text/javascript">										 
							 $(document).ready(function() {	
						 		$('#{$MODULE}_detail_basicAction_Process').click(function(){						 			
							        var ids1 = new Array();						 
							        var ids=$("#recordId").val();
							        ids1.push(ids);						            
							        
						            var ajax_data1 = {
						            "userid" : $("#current_user_id").val(),						
									"accion" : "procesarLocalizadores",					
									"id" : ids1					
									};		
									jQuery.ajax({
										data: ajax_data1,
										url: 'modules/Localizadores/ajaxProcesarList_Loc.php',
										type: 'get',
										success: function(response){	
											var idloc=$("#recordId").val();																								
											if (response=="Completado"){
												bootbox.alert("Se han procesado TODOS LOS BOLETOS seleccionados.");										
												setTimeout(function(){ window.location.assign("index.php?module=Localizadores&view=Detail&record="+idloc); }, 3000);
											}
											if (response=="Incompleto"){
												bootbox.alert("Algunos boletos NO se procesaron por FALTA DE CONTACTO asociado.");										
												setTimeout(function(){ window.location.assign("index.php?module=Localizadores&view=List"); }, 3000);
											}
											if (response=="Fallido"){
												bootbox.alert("No se procesó ningún localizador por falta de contactos."); 											
											}	
										}
									});
							    });	
							});						
							</script>
							{/if}&nbsp;
							<!-- FIN BOTON DE ACCION PROCESAR EN VISTA DETALLE DEL LOCALIZADOR, may2016 !-->				   

							{foreach item=DETAIL_VIEW_BASIC_LINK from=$DETAILVIEW_LINKS['DETAILVIEWBASIC']}
							<span class="btn-group">
								<button class="btn" id="{$MODULE_NAME}_detailView_basicAction_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_BASIC_LINK->getLabel())}"
									{if $DETAIL_VIEW_BASIC_LINK->isPageLoadLink()}
										onclick="window.location.href='{$DETAIL_VIEW_BASIC_LINK->getUrl()}'"
									{else}
										onclick={$DETAIL_VIEW_BASIC_LINK->getUrl()}
									{/if}>
									<strong>{vtranslate($DETAIL_VIEW_BASIC_LINK->getLabel(), $MODULE_NAME)}</strong>
								</button>
							</span>
							{/foreach}
							{if $DETAILVIEW_LINKS['DETAILVIEW']|@count gt 0}
							<span class="btn-group">
								<button class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
									<strong>{vtranslate('LBL_MORE', $MODULE_NAME)}</strong>&nbsp;&nbsp;<i class="caret"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									{foreach item=DETAIL_VIEW_LINK from=$DETAILVIEW_LINKS['DETAILVIEW']}
									<li id="{$MODULE_NAME}_detailView_moreAction_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_LINK->getLabel())}">
										<a href={$DETAIL_VIEW_LINK->getUrl()} >{vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE_NAME)}</a>
									</li>
									{/foreach}
								</ul>
							</span>
							{/if}
							{if $DETAILVIEW_LINKS['DETAILVIEWSETTING']|@count gt 0}
								<span class="btn-group">
									<button class="btn dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-wrench" alt="{vtranslate('LBL_SETTINGS', $MODULE_NAME)}" title="{vtranslate('LBL_SETTINGS', $MODULE_NAME)}"></i>&nbsp;&nbsp;<i class="caret"></i></button>
									<ul class="listViewSetting dropdown-menu">
										{foreach item=DETAILVIEW_SETTING from=$DETAILVIEW_LINKS['DETAILVIEWSETTING']}
											<li><a href={$DETAILVIEW_SETTING->getUrl()}>{vtranslate($DETAILVIEW_SETTING->getLabel(), $MODULE_NAME)}</a></li>
										{/foreach}
									</ul>
								</span>
							{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
			{if !{$NO_PAGINATION}}
				<div class="span2 detailViewPagingButton">
					<span class="btn-group pull-right">
						<button class="btn" id="detailViewPreviousRecordButton" {if empty($PREVIOUS_RECORD_URL)} disabled="disabled" {else} onclick="window.location.href='{$PREVIOUS_RECORD_URL}'" {/if}><i class="icon-chevron-left"></i></button>
						<button class="btn" id="detailViewNextRecordButton" {if empty($NEXT_RECORD_URL)} disabled="disabled" {else} onclick="window.location.href='{$NEXT_RECORD_URL}'" {/if}><i class="icon-chevron-right"></i></button>
					</span>
				</div>
			{/if}
		</div>
		<div class="detailViewInfo row-fluid">
			<div class="{if $NO_PAGINATION} span12 {else} span10 {/if} details">
				<form id="detailView" data-name-fields='{ZEND_JSON::encode($MODULE_MODEL->getNameFields())}'>
					<div class="contents">
{/strip}
