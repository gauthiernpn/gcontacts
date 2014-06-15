@if(isset($contact))
@foreach($contact['im'] as $index => $im)
<h4 class="im" data-index="{{$index}}">IM address #{{$index+1}} <small><a  class="removeRow btn btn-danger btn-xs" data-index="{{$index}}" data-type="im"><span  data-index="{{$index}}" data-type="im" class="glyphicon glyphicon-trash"></span></a></small></h4>
<table class="table table-bordered table-striped im" data-index="{{$index}}">
    <tr>
        <td>Type</td>
        <td>
            <select name="im[{{$index}}][rel]" class="form-control">
                @foreach(\GContacts\AtomType\Im::getDefaultRels() as $imRel)
                @if(!is_null($imRel))
                @if(isset($im['rel']) && $imRel == $im['rel'])
                <option selected="selected" value="{{$imRel}}" label="{{$imRel}}">{{$imRel}}</option>
                @else
                <option value="{{$imRel}}" label="{{$imRel}}">{{$imRel}}</option>
                @endif
                @endif
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>Label</td>
        <td><input type="text" name="im[{{$index}}][label]" value="{{{$im['label'] or ''}}}" placeholder="Label"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>Is primary?</td>
        <td>
                @if(isset($im['primary']) && $im['primary'])
                <input type="radio" name="im[{{$index}}][primary]" value="1" checked="checked"/>
                @else
                <input type="radio" name="im[{{$index}}][primary]" value="1"/>
                @endif
        </td>
    </tr>
    <tr>
        <td>Protocol</td>
        <td>
            <select name="im[{{$index}}][protocol]" class="form-control">
                @foreach(\GContacts\AtomType\Im::getDefaultProtocols() as $imProtocol)
                @if(!is_null($imProtocol))
                @if(isset($im['protocol']) && $imProtocol == $im['protocol'])
                <option selected="selected" value="{{$imProtocol}}" label="{{$imProtocol}}">{{$imProtocol}}</option>
                @else
                <option value="{{$imProtocol}}" label="{{$imProtocol}}">{{$imProtocol}}</option>
                @endif
                @endif
                @endforeach
            </select>
        </td>
        </tr>
    <tr>
        <td>Address</td>
        <td><input type="text" name="im[{{$index}}][address]" value="{{{$im['address'] or ''}}}" placeholder="IM address"
                   class="form-control"/></td>
    </tr>
</table>
@endforeach
@endif