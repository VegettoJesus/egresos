<header>
    <div class="left">
        <div class="menu-container">
            <div class="menu" id="menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="brand">
            <img src="{{ asset('img/logoCorp.png') }}" alt="icon-ArzobispoLoayza" id="imgLogo" class="logo">
            <span class="name" id="nameLogo">Egresos Loayza</span>
        </div>
    </div>
    <div class="right">
        <p id="name-user">{{ Auth::user()->Nombre }}</p>
        <img src="{{ asset('img/user.png') }}" class="user" id="user" alt="user">
    </div>
</header>
