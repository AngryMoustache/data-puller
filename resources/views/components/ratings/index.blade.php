<ul class="flex gap-8 w-full">
    @foreach ($ratings as $rating)
        <li class="w-full">
            <x-ratings.rating :rating="$rating" />
        </li>
    @endforeach
</ul>
