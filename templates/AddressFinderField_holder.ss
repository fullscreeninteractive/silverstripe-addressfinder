<div class="address_finder"  data-api-key="$ApiKey">
	<div id="$Name" class="address_finder_address field<% if $extraClass %> $extraClass<% end_if %>" style="display: none;">
		<% if $Title %><label class="left" for="$ID">$Title</label><% end_if %>

		<div class="middleColumn">
			$AddressField
		</div>

		<% if $RightTitle %><label class="right" for="$ID">$RightTitle</label><% end_if %>
		<% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
		<% if $Description %><span class="description">$Description</span><% end_if %>

		<div class='address_finder_attribution'>
			<p><a href='http://addressfinder.co.nz'>AddressFinder</a> provided by <a href='http://www.abletech.co.nz/'>Able Technology</a></p>
		</div>
	</div>

	<div class="toggle_manual_address" style="display: none">
		<p><a href="#"><% _t('AddressFinderField.ENTERMANUAL', 'Enter your address manually') %></a></p>
	</div>

	<div class="manual_address">
		<% loop ManualAddressFields %>
			$FieldHolder
		<% end_loop %>
	</div>
</div>
