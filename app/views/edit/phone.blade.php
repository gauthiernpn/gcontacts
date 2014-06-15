@if(isset($contact))
@foreach($contact['phoneNumber'] as $index => $phone)
<h4 class="phoneNumber" data-index="{{$index}}">Phone nr. #{{$index+1}}  <small><a href="#" class="removeRow btn btn-danger btn-xs" data-index="{{$index}}" data-type="phoneNumber"><span  data-index="{{$index}}" data-type="phoneNumber" class="glyphicon glyphicon-trash"></span></a></small></h4>
<table class="table table-striped table-bordered phoneNumber" data-index="{{$index}}">
    <tr>
        <td style="width:30%;">Type</td>
        <td>
            <select name="phone[{{$index}}][rel]" class="form-control">
                <option selected="selected" value="Other" label="(no type)">(no type)</option>
                @foreach(\GContacts\AtomType\Phonenumber::getDefaultRels() as $phoneRel)
                @if(isset($phone['rel']) && $phoneRel == $phone['rel'])
                <option selected="selected" value="{{$phoneRel}}" label="{{$phoneRel}}">{{$phoneRel}}</option>
                @else
                <option value="{{$phoneRel}}" label="{{$phoneRel}}">{{$phoneRel}}</option>
                @endif
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>Label</td>
        <td><input type="text" name="phone[{{$index}}][label]" value="{{{$phone['label'] or ''}}}" placeholder="Label"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>Is primary?</td>
        @if(isset($phone['primary']) && $phone['primary'])
        <td><input type="radio" name="phoneprimary" value="{{$index}}" checked="checked"/></td>
        @else
        <td><input type="radio" name="phoneprimary" value="{{$index}}"/></td>
        @endif
    </tr>
    <tr>
        <td>Number</td>
        <td><input type="text" name="phone[{{$index}}][number]" value="{{{$phone['number'] or ''}}}"
                   placeholder="Phone number" class="form-control"/></td>
    </tr>
</table>
@endforeach
@endif