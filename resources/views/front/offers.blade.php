@if(!is_null($offers))
	<div class="offer">
		Anúncios
		<div class="owl-offer owl-carousel owl-theme">
		    @foreach($offers as $offer)
		    	@if($offer->status == 1)
		    	<div class="item">
		    		<img src="{{$offer->cover}}"/>
		    		<div class="description">{{$offer->name}}</div>
		    	</div>
		    	@endif
		    @endforeach
		</div>
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
			$(".owl-offer").owlCarousel({
		        //autoPlay: 3000,
		        items: 1,
	            loop:true,
			    margin:10,
			    nav:false,
			    dots: true
		    });	
		});
	</script>
@endif