<template>
<!--    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">-->
<!--        <div class="container">-->
<!--            <a class="navbar-brand" href="#page-top">Welcome</a>-->
<!--            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                Menu-->
<!--                <i class="fas fa-bars"></i>-->
<!--            </button>-->
<!--            <div class="collapse navbar-collapse" id="navbarResponsive">-->
<!--                <ul class="navbar-nav ms-auto">-->
<!--                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#contact">Contact</a></li>-->
<!--                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/books">Books</a></li>-->
<!--                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/bookshelf">Bookshelf</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
<!--    </nav>-->



    <nav>
        <div>
            <a href="/">Welcome</a>
            <a href="/books"> Books </a>
            <a href="/bookshelf"> Bookshelf</a>
            <a href="/login"> Login</a>
        </div>
    </nav>


<!--    <nav>-->
<!--        <ul class="flex space-x-6">-->
<!--            <li>-->
<!--                <NavLink href="/" :active="$page.component === 'Home'">-->
<!--                    Home-->
<!--                </NavLink>-->
<!--            </li>-->

<!--            <li>-->
<!--                <NavLink href="/books" :active="$page.component === 'Books'">-->
<!--                    Books-->
<!--                </NavLink>-->
<!--            </li>-->

<!--            <li>-->
<!--                <NavLink href="/users" :active="$page.component === 'Users'">-->
<!--                    Users-->
<!--                </NavLink>-->
<!--            </li>-->

<!--            <li>-->
<!--                <NavLink href="/users/create" :active="$page.component === 'Register'">-->
<!--                    Register-->
<!--                </NavLink>-->
<!--            </li>-->

<!--            <li>-->
<!--                <NavLink href="/logout" method="post" as="button">-->
<!--                    Log Out-->
<!--                </NavLink>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </nav>-->
</template>

<script>
import NavLink from "./NavLink";

export default {
    components: { NavLink },
};
</script>
