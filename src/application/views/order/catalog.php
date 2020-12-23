<?php
defined('BASEPATH') or exit ('No direct script access allowed');
// $show_names = TRUE;
// variety_order.php Chris Dart Mar 4, 2013 8:44:25 PM
// chrisdart@cerebratorium.com

if ( ! empty($orders)) :
	?>

	<!-- views/order/catalog.php -->
	<h5 class="column-instructions">
		Click on a header to hide the column [
		<a href="#" class=" reset-columns">Reset</a>
		]
	</h5>
	<table class="list catalog hideable-columns">
		<thead>

		<tr>
			<th class="hide-column"></th>
			<?php if ( ! $show_names): ?>
				<th class="hide-column">Year</th>
			<?php endif; ?>
			<th class="hide-column">Grower</th>
			<th class="hide-column">Cat&#35;</th>
			<?php if ($show_names): ?>
				<th class="hide-column">Genus</th>
				<th class="hide-column">Species</th>
				<th class="hide-column">Common</th>
				<th class="hide-column">Variety</th>
			<?php endif; ?>
			<th class="hide-column">Presale Order</th>
			<?php if ($year == 2021): ?>
				<th class="hide-column">Friday Order</th>
				<th class="hide-column">Saturday Order</th>
			<?php else: ?>
				<th class="hide-column">Midsale Order</th>

			<?php endif; ?>
			<th class="hide-column">Total</th>
			<th class="hide-column">Pot Size</th>
			<th class="hide-column">Flat Size</th>
			<th class="hide-column">Flat Cost</th>
			<th class="hide-column">Plant Cost</th>
			<th class="hide-column">Order Total</th>
			<th class="hide-column">Price</th>
			<th class="hide-column">Grower Code</th>
			<th class="hide-column"
				title="Include or exclude these items from the flat totals calculator on the home page">
				Excluded from flat totals
			</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$presale_total = 0;
		$midsale_total = 0;
		$flat_cost_total = 0;
		foreach ($orders as $order) :
			$flat_cost = $order->flat_cost;
			$plant_cost = $order->plant_cost;
			if ($order->flat_cost && ! $order->plant_cost)
			{
				$flat_cost = $order->flat_cost;
				if ($order->flat_cost != 0)
				{
					$plant_cost = $order->flat_size / $order->flat_cost;
				}
				else
				{
					$plant_cost = 0;
				}
			}
			elseif ($order->plant_cost && ! $order->flat_cost)
			{
				$plant_cost = $order->plant_cost;
				$flat_cost = $order->flat_size * $order->plant_cost;
			}
			$row_classes = [
					"grouping",
			];
			$latest_year = get_value($order, "latest_year", TRUE);

			if (get_value($order, "has_reorder") && $order->has_reorder)
			{
				$row_classes [] = "hidden";
			}
			if ($order->received_presale == "0.000")
			{
				$row_classes [] = "crop-failure";
			}

			$presale_total += $order->count_presale;
			$midsale_total += $order->count_midsale;
			$flat_cost_total += $order->flat_cost * ($order->count_presale + $order->count_midsale);
			$row_classes [] = has_price_discrepancy($order);
			?>
			<tr class="<?php echo implode(" ", $row_classes); ?>"
				id="order_<?php echo $order->id; ?>"
				data-order-id="<?php print $order->id; ?>">
				<td class="no-wrap">
					<?php if (IS_ADMIN): ?>
						<span class="omit-row omit button"
							  id="omit-order_<?php echo $order->id; ?>">Omit</span>
					<?php endif; ?>
					<?php if (IS_EDITOR): ?>
						<?php echo create_button([
								"text" => "Edit",
								"href" => site_url("order/edit/$order->id"),
								"class" => [
										"button",
										"edit",
										"dialog",
										"edit-order",
								],
								"id" => sprintf("edit-order_%s", $order->id),
						]); ?>

					<?php else: ?>
						<?php echo create_button([
								"text" => "Details",
								"class" => ["button", "details"],
								"href" => site_url("order/view/$order->id"),
						]); ?>

					<?php endif; ?>

				</td>
				<?php if ( ! $show_names): ?>
					<td class="order-year field"><?php echo edit_field("year", $order->year, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
				<?php endif; ?>
				<td class="order-grower_id field"><?php echo edit_field("grower_id", $order->grower_id, "", "order", $order->id, ["envelope" => "span"]); ?>
				</td>
				<td class="order-catalog_number field">
					<!-- if there is no catalog number, show the first letter of the category -->
					<?php echo edit_field("catalog_number", $order->catalog_number ? $order->catalog_number : ucfirst(substr($order->category, 0, 1)), "", "order", $order->id, ["envelope" => "span"]); ?>
				</td>
				<?php if (!empty($show_names)): ?>
					<td>
						<a href="<?php echo site_url(sprintf("common/find?genus=%s", $order->genus)); ?>"
						   title="View all <?php echo $order->genus; ?>"><?php echo $order->genus; ?></a>
					</td>
					<td><?php echo $order->species; ?></td>
					<td>
						<a href="<?php echo site_url("common/view/$order->common_id"); ?>"
						   title="View the details for <?php echo $order->name; ?>"><?php echo $order->name; ?></a>
					</td>
					<td>
						<a style="font-weight: bold"
						   href="<?php echo site_url("variety/view/$order->variety_id"); ?>"
						   title="View the details for <?php echo $order->variety; ?>"><?php echo $order->variety; ?></a>
					</td>
				<?php endif; ?>
				<td class="order-count_presale field">
					<?php echo edit_field("count_presale", $order->count_presale, "", "order", $order->id, ["envelope" => "span"]); ?>
				</td>
				<?php if($year == 2021):?>
					<td class="order-count_friday field">
						<?php echo edit_field("count_friday", $order->count_friday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-count_saturday field">
						<?php echo edit_field("count_saturday", $order->count_saturday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
				<?php else: ?>
					<td class="order-count_midsale field">
						<?php echo edit_field("count_midsale", $order->count_midsale, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
				<?php endif; ?>
				<?php if (!empty($is_inventory)): ?>
					<td class="order-received_presale field">
						<?php echo edit_field("received_presale", $order->received_presale, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-remainder_friday field">
						<?php echo edit_field("remainder_friday", $order->remainder_friday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-sellout_friday field">
						<?php echo edit_field("sellout_friday", $order->sellout_friday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-received_midsale field">
						<?php echo edit_field("received_midsale", $order->received_midsale, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-remainder_saturday field">
						<?php echo edit_field("remainder_saturday", $order->remainder_saturday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-sellout_saturday field">
						<?php echo edit_field("sellout_saturday", $order->sellout_saturday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-remainder_sunday field">
						<?php echo edit_field("remainder_sunday", $order->remainder_sunday, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
					<td class="order-count_dead field">
						<?php echo edit_field("count_dead", $order->count_dead, "", "order", $order->id, ["envelope" => "span"]); ?>
					</td>
				<?php endif; ?>
				<td class="order-total_plants field">
					<?php echo $order->count_midsale + $order->count_presale; ?>
				</td>
				<td class="order-pot_size field no-wrap"><?php echo edit_field("pot_size", $order->pot_size, "", "order", $order->id, [
							"envelope" => "span",
							"class" => "pot-size",
					]); ?>
				</td>
				<td class="order-flat_size field cost-field" id="flat_size">
					<span id="edit-flat-size_<?php echo $order->id; ?>"
						  class="edit-cost"><?php echo $order->flat_size; ?></span>

				</td>
				<td class="order-flat_cost field cost-field no-wrap"
					id="flat_cost">
					$
					<span id="edit-flat-cost_<?php echo $order->id; ?>"
						  class="edit-cost"><?php echo number_format($order->flat_cost, 2); ?></span>
				</td>
				<td class="order-plant_cost field cost-field no-wrap"
					id="plant_cost">
					$
					<span id="edit-plant-cost_<?php echo $order->id; ?>"
						  class="edit-cost"><?php echo number_format($order->plant_cost, 2); ?></span>
				</td>
				<td class="order-order_total field order_total no-wrap">
					$<?php echo round($order->flat_cost * ($order->count_presale + $order->count_midsale), 2); ?>
				</td>
				<td class="order-price field price-field no-wrap">
					$<?php echo edit_field("price", $order->price, "", "order", $order->id, ["envelope" => "span"]); ?>
				</td>
				<td class="order-grower_code field"><?php echo edit_field("grower_code", $order->grower_code, "", "order", $order->id, ["envelope" => "span"]); ?>
				</td>
				<td class="order-flat_exclude field">
					<?php if ( ! empty($order->flat_exclude_button)): ?>
						<?php print $order->flat_exclude_button; ?>
					<?php endif; ?>
				</td>
				<td class="re-order field">
					<?php echo create_button([
							"text" => "Re-order",
							"href" => site_url("order/create?variety_id=$order->variety_id&reorder=1"),
							"id" => "oc_$order->variety_id",
							"class" => [
									"button",
									"new",
									"create",
									"dialog",
									"order-create",
							],
					]); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
		<tr>
			<td></td>
			<?php if ( ! $show_names): ?>
				<td></td>
			<?php endif; ?>
			<td></td>
			<td></td>
			<?php if ($show_names): ?>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			<?php endif; ?>
			<th><?php echo number_format($presale_total); ?></th>
			<th><?php echo number_format($midsale_total); ?></th>
			<th><?php echo number_format($presale_total + $midsale_total); ?></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th><?php echo get_as_price($flat_cost_total); ?></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		</tfoot>
	</table>

<?php endif; ?>
