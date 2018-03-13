<?php
use Jigoshop\Admin\Helper\Forms;
?>

<tr>
	<td>
		<span class="glyphicon glyphicon-sort"></span>
	</td>
	<td>
		<?php 
		Forms::select([
			'name' => sprintf('processingFeeRules[%s][methods]', $id),
			'options' => $methods,
			'multiple' => true,
			'value' => isset($methodsSelected)?$methodsSelected:[]
		]);
		?>
	</td>
	<td>
		<?php
		Forms::text([
			'name' => sprintf('processingFeeRules[%s][minValue]', $id),
			'placeholder' => __('Leave blank for unlimited.', 'jigoshop-ecommerce'),
			'value' => isset($minValue)?$minValue:''
		]);
		?>
	</td>
	<td>
		<?php
		Forms::text([
			'name' => sprintf('processingFeeRules[%s][maxValue]', $id),
			'placeholder' => __('Leave blank for unlimited.', 'jigoshop-ecommerce'),
			'value' => isset($maxValue)?$maxValue:''
		]);
		?>
	</td>		
	<td>
		<?php
		Forms::text([
			'name' => sprintf('processingFeeRules[%s][value]', $id),
			'placeholder' => __('Absolute value or percentage of order value.', 'jigoshop-ecommerce'),
			'value' => isset($value)?$value:''
		]);
		?>
	</td>
	<td>
		<?php 
		Forms::checkbox([
			'name' => sprintf('processingFeeRules[%s][mode]', $id),
			'classes' => ['switch-medium'],
			'checked' => isset($mode)?$mode:false
		]);
		?>
	</td>
	<td>
		<a class="btn btn-default processing-fee-remove-rule">
			<span class="glyphicon glyphicon-remove"></span>
		</a>
	</td>
</tr>