<table class="table table-bordered table-striped">
    <tr>
        <td style="width:30%;">
            Full name<br />
            <span class="small">(auto generated)</span>
        </td>
        <td><span id="fullName">{{{$contact['name']['fullName'] or ''}}}</span></td>
    </tr>
    <tr>
        <td>
            Name prefix<br />
            <span class="small">e.g. Dr.</span>
        </td>
        <td><input type="text" name="namePrefix" value="{{{$contact['name']['namePrefix'] or ''}}}" placeholder="Name Prefix" class="form-control" /></td>
    </tr>
    <tr>
        <td>Given name</td>
        <td><input type="text" name="givenName" value="{{{$contact['name']['givenName'] or ''}}}" placeholder="Given name" class="form-control" /></td>
    </tr>
    <tr>
        <td>
            Additional name<br />
            <span class="small">(middle name, nickname)</span>
        </td>
        <td><input type="text" name="additionalName" value="{{{$contact['name']['additionalName'] or ''}}}" placeholder="Additional name" class="form-control" /></td>
    </tr>
    <tr>
        <td>Family name</td>
        <td><input type="text" name="familyName" value="{{{$contact['name']['familyName'] or ''}}}" placeholder="Family name" class="form-control" /></td>
    </tr>
        <td>
            Name Suffix<br />
            <span class="small">e.g. MA</span>
        </td>
        <td><input type="text" name="nameSuffix" value="{{{$contact['name']['nameSuffix'] or ''}}}" placeholder="Name Suffix" class="form-control" /></td>
    </tr>
</table>