@if(isset($contact))
@foreach($contact['organization'] as $index => $org)
<h4 class="organization" data-index="{{$index}}">Organization nr. #{{$index+1}} <small><a href="#" class="removeRow btn btn-danger btn-xs" data-index="{{$index}}" data-type="organization"><span  data-index="{{$index}}" data-type="organization" class="glyphicon glyphicon-trash"></span></a></small></h4>
<table class="table table-striped table-bordered organization" data-index="{{$index}}">
    <tr>
    <td style="width:30%;">Type</td>
    <td>
        <select name="organization[{{$index}}][rel]" class="form-control">
            <option selected="selected" value="Other" label="(no type)">(no type)</option>
            @foreach(\GContacts\AtomType\Organization::getDefaultRels() as $orgRel)
            @if(isset($org['rel']) && $orgRel == $org['rel'])
            <option selected="selected" value="{{$orgRel}}" label="{{$orgRel}}">{{$orgRel}}</option>
            @else
            <option value="{{$orgRel}}" label="{{$orgRel}}">{{$orgRel}}</option>
            @endif
            @endforeach
        </select>
    </td>
    </tr>
    <tr>
        <td>Label</td>
        <td><input type="text" name="organization[{{$index}}][label]" class="form-control" placeholder="Label" value="{{$org['label'] or ''}}" /></td>
    </tr>
    <tr>
        <td>Primary?</td>
        @if(isset($org['primary']) && $org['primary'])
        <td><input type="radio" name="orgprimary" value="{{$index}}" checked="checked" /></td>
        @else
        <td><input type="radio" name="orgprimary" value="{{$index}}" /></td>
        @endif
    </tr>
    <tr>
        <td>Organization name</td>
        <td><input type="text" name="organization[{{$index}}][orgname]" class="form-control" placeholder="Label" value="{{$org['orgName'] or ''}}" /></td>
    </tr>
    <tr>
        <td>Organization symbol</td>
        <td><input type="text" name="organization[{{$index}}][orgsymbol]" class="form-control" placeholder="Label" value="{{$org['orgSymbol'] or ''}}" /></td>
    </tr>
    <tr>
        <td>Organization department</td>
        <td><input type="text" name="organization[{{$index}}][orgdepartment]" class="form-control" placeholder="Label" value="{{$org['orgDepartment'] or ''}}" /></td>
    </tr>
    <tr>
        <td>Title in organization</td>
        <td><input type="text" name="organization[{{$index}}][orgtitle]" class="form-control" placeholder="Label" value="{{$org['orgTitle'] or ''}}" /></td>
    </tr>
    <tr>
        <td>Job description</td>
        <td><input type="text" name="organization[{{$index}}][orgjobdescription]" class="form-control" placeholder="Label" value="{{$org['orgJobDescription'] or ''}}" /></td>
    </tr>
    <tr>
        <td>
            Where<br />
            <span class="small">A location</span>
        </td>
        <td><input type="text" name="organization[{{$index}}][where]" class="form-control" placeholder="Where" value="{{$org['where'] or ''}}" /></td>
    </tr>


</table>
@endforeach
@endif