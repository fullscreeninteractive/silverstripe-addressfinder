<div class="address_finder"  data-api-key="$ApiKey">
    <div id="$Name" class="address_finder_address form-group field text<% if $extraClass %> $extraClass<% end_if %>" style="display: none;">
        <% if $Title %><label class="form__field-label" for="$ID">$Title</label><% end_if %>

        <div class="form__field-holder">
            <div class="addressfinder__holder" style="position: relative">
             $AddressField
            </div>

            <% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
            <% if $Description %><p class="form__field-description">$Description</p><% end_if %>

            <div class='address_finder_attribution'>
                <p><a href='http://addressfinder.co.nz'>AddressFinder</a> provided by <a href='http://www.abletech.co.nz/'>Able Technology</a></p>
            </div>

            <% if ShowManualFields %>
                <div class="toggle_manual_address" style="display: none">
                    <p><a href="#"><% _t('AddressFinderField.ENTERMANUAL', 'Or, enter your address manually') %></a></p>
                </div>
            <% end_if %>
        </div>

        <% if $RightTitle %><p class="form__field-extra-label" id="extra-label-$ID">$RightTitle</p><% end_if %>
    </div>

    <% if ShowManualFields %>

        <div class="manual_address">
            $ManualToggleField

            <% loop ManualAddressFields %>
                $FieldHolder
            <% end_loop %>
        </div>
    <% end_if %>
</div>
