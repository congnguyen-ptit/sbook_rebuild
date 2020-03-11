<div class="cart-main-area mb-70">
    <div class="container">
        <div class="row">
            <div class="suggestion">
                <div class="tab-content">
                    <div class="tab-pane {{ $page == trans('page.summary') ? 'active' : 'fade' }}" id="title">
                        @include('layout.section.search.titles')
                    </div>
                    <div class="tab-pane {{ $page == trans('page.book.author') ? 'active' : 'fade' }}" id="author">
                        @include('layout.section.search.authors')
                    </div>
                    <div class="tab-pane {{ $page == trans('page.book.description') ? 'active' : 'fade' }}" id="description">
                        @include('layout.section.search.descriptions')
                    </div>
                    <div class="tab-pane {{ $page == trans('settings.admin.layout.users') ? 'active' : 'fade' }}" id="users">
                        @include('layout.section.search.users')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
