<div class="sidebar sidebar-category">
	<h4> Categorias </h4>
	<ul class="nav sidebar-menu">
	    @foreach($categories_list as $category)
	        @if($category->children()->count() > 0)
	            <li>@include('layouts.front.category-sidebar-sub', ['subs' => $category->children])</li>
	        @else
	            <li @if(request()->segment(2) == $category->slug || $category->side_bar_active) class="active" @endif><a href="{{ route('front.category.slug', $category->slug) }}">{{ $category->name }}</a></li>
	        @endif
	    @endforeach
	</ul>
</div>