@if(isset($contact))
@foreach($contact['structuredPostalAddress'] as $index => $address)
<h4 class="address" data-index="{{$index}}">Address #{{$index+1}}  <small><a class="removeRow btn btn-danger btn-xs" data-index="{{$index}}" data-type="address"><span  data-index="{{$index}}" data-type="address" class="glyphicon glyphicon-trash"></span></a></small></h4>
<table class="table table-bordered table-striped address" data-index="{{$index}}">
    <tr>
        <td style="width:30%;">Type</td>
        <td>
            <select name="address[{{$index}}][rel]" class="form-control">
                @foreach(\GContacts\AtomType\StructuredPostalAddress::getDefaultRels() as $addressRel)
                @if(!is_null($addressRel))
                @if(isset($address['rel']) && $addressRel == $address['rel'])
                <option selected="selected" value="{{$addressRel}}" label="{{$addressRel}}">{{$addressRel}}</option>
                @else
                <option value="{{$addressRel}}" label="{{$addressRel}}">{{$addressRel}}</option>
                @endif
                @endif
                @endforeach
            </select>
        </td>
        </tr>
    <tr>
        <td>
            Mail class<br />
            <span class="small">The type of mail that can be received at this address</span>
        </td>
        <td>
            <select name="address[{{$index}}][mailclass]" class="form-control">
                @foreach(\GContacts\AtomType\StructuredPostalAddress::getDefaultMailClasses() as $addressMailClass)
                @if(!is_null($addressMailClass))
                @if(isset($address['mailClass']) && $addressMailClass == $address['mailClass'])
                <option selected="selected" value="{{$addressMailClass}}" label="{{$addressMailClass}}">
                    {{$addressMailClass}}
                </option>
                @else
                <option value="{{$addressMailClass}}" label="{{$addressMailClass}}">{{$addressMailClass}}</option>
                @endif
                @endif
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>
            Usage<br />
            <span class="small">Usage indicates whether this address will "work" locally only or from anywhere.</span>
        </td>
        <td>
            <select name="address[{{$index}}][usage]" class="form-control">
                @foreach(\GContacts\AtomType\StructuredPostalAddress::getDefaultUsages() as $addressUsage)
                @if(!is_null($addressUsage))
                @if(isset($address['usage']) && $addressUsage == $address['usage'])
                <option selected="selected" value="{{$addressUsage}}" label="{{$addressUsage}}">{{$addressUsage}}
                </option>
                @else
                <option value="{{$addressUsage}}" label="{{$addressUsage}}">{{$addressUsage}}</option>
                @endif
                @endif
                @endforeach
            </select>
        </td>
        </tr>
    <tr>
        <td>Label</td>
        <td>
            <input type="text" name="address[{{$index}}][label]" value="{{{$address['label'] or ''}}}" placeholder="Label"
                   class="form-control"/>
        </td>
    </tr>
    <tr>
        <td>Primary address?</td>
        <td>
                @if(isset($address['primary']) && $address['primary'])
                <input type="radio" name="address[{{$index}}][primary]" value="1" checked="checked"/>
                @else
                <input type="radio" name="address[{{$index}}][primary]" value="1"/>
                @endif
        </td>
        </tr>
    <tr>
        <td>
            Agent<br />
            <span class="small">The agent who actually receives the mail. Used in work addresses. Also for 'in care of' or 'c/o'.</span>
        </td>
        <td>
            <input type="text" name="address[{{$index}}][agent]" value="{{{$address['agent'] or ''}}}" placeholder="Agent"
                   class="form-control"/>
        </td>
    </tr>
    <tr>
        <td>
            House name<br />
            <span class="small">Where the streets have no numbers to indicate houses.</span>
        </td>
        <td><input type="text" name="address[{{$index}}][housename]" value="{{{$address['housename'] or ''}}}"
                   placeholder="House name" class="form-control"/></td>
        </tr>
    <tr>
        <td>Street (+ number)</td>
        <td><input type="text" name="address[{{$index}}][street]" value="{{{$address['street'] or ''}}}" placeholder="Street"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>Zip code</td>
        <td><input type="text" name="address[{{$index}}][postcode]" value="{{{$address['postcode'] or ''}}}"
                   placeholder="Postcode" class="form-control"/></td>
    </tr>
    <tr>
        <td>PO Box</td>
        <td><input type="text" name="address[{{$index}}][pobox]" value="{{{$address['pobox'] or ''}}}" placeholder="PO Box"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>Neighborhood</td>
        <td><input type="text" name="address[{{$index}}][neighborhood]" value="{{{$address['neighborhood'] or ''}}}"
                   placeholder="Neighborhood" class="form-control"/></td>
    </tr>
    <tr>
        <td>City</td>
        <td><input type="text" name="address[{{$index}}][city]" value="{{{$address['city'] or ''}}}" placeholder="City"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>
            Subregion<br />
            <span class="small">County, municipality</span>
        </td>
        <td><input type="text" name="address[{{$index}}][subregion]" value="{{{$address['subregion'] or ''}}}"
                   placeholder="Subregion" class="form-control"/></td>
        </tr>
    <tr>
        <td>Region<br />
        <span class="small">State, province</span></td>
        <td><input type="text" name="address[{{$index}}][region]" value="{{{$address['region'] or ''}}}" placeholder="Region"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>Country</td>
        <td><input type="text" name="address[{{$index}}][country]" value="{{{$address['country'] or ''}}}"
                               placeholder="Country" class="form-control"/></td>
    </tr>
</table>
@endforeach
@endif