<div class="col-xs-12">
    <h2>Quick Links</h2>
    @foreach(\Soda\Cms\Database\Models\Quicklink::where('user_id', Auth::guard('soda')->user()->id)->get() as $quicklink)
        <a href="{{ $quicklink->getUrl() }}" class="tag">
          <span>{{ $quicklink->text }}</span>
          <span class="tag-delete">&times;</span>
        </a>
    @endforeach
</div>
