		Заказано:
		<table width="40%">
			
		{if !empty($basket)}
			{foreach item=Good from=$basket}
			<tr>
				<td>	
					{$Good.goodId}
				</td>
				<td>
					{$Good.count}
				</td>
				<td>{if !empty($Good.title)}
						{$Good.title}
					{/if}
				</td>
			</tr>
			{/foreach}
		{/if}
		
		</table>