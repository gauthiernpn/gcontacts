@if(isset($contact))
@foreach($contact['email'] as $index => $email)
<h4 class="email" data-index="{{$index}}">Email address #{{$index+1}} <small><a class="removeRow btn btn-danger btn-xs" data-index="{{$index}}" data-type="email"><span  data-index="{{$index}}" data-type="email" class="glyphicon glyphicon-trash"></span></a></small></h4>
<table class="table table-bordered table-striped email" data-index="{{$index}}">
    <tr>
        <td>Type</td>
        <td>
            <select name="email[{{$index}}][rel]" class="form-control">
                <option selected="selected" value="Other" label="(no type)">(no type)</option>
                @foreach(\GContacts\AtomType\Email::getDefaultRels() as $emailRel)
                @if(isset($email['rel']) && $emailRel == $email['rel'])
                <option selected="selected" value="{{$emailRel}}" label="{{$emailRel}}">{{$emailRel}}</option>
                @else
                <option value="{{$emailRel}}" label="{{$emailRel}}">{{$emailRel}}</option>
                @endif
                @endforeach
            </select>
        </td>
        </tr>
    <tr>
        <td>Label</td>
        <td><input type="text" name="email[{{$index}}][label]" value="{{{$email['label'] or ''}}}" placeholder="Label"
                   class="form-control"/></td>
    </tr>
    <tr>
        <td>Is primary?</td>
        @if(isset($email['primary']) && $email['primary'])
        <td><input type="radio" name="emailprimary" value="{{$index}}" checked="checked"/></td>
        @else
        <td><input type="radio" name="emailprimary" value="{{$index}}" /></td>
        @endif
        </tr>
    <tr>
        <td>Email address</td>
        <td><input type="text" name="email[{{$index}}][address]" value="{{{$email['address'] or ''}}}"
                   placeholder="Email address" class="form-control"/></td>
    </tr>
</table>
@endforeach
@endif