@if(isset($status))
    @if($status == 1)
        <span style="display: none; visibility: hidden">1</span>
        <span class="text-success">Ativo</span>
        <!-- <button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button> -->
        @else
        <span style="display: none; visibility: hidden">0</span>
        <span class="text-danger">Inativo</span>
        <!-- <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button> -->
    @endif
@endif