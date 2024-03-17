<template>
<!--    <Head>-->
<!--        <meta-->
<!--            type="description"-->
<!--            content="Information about my app"-->
<!--            head-key="description"-->
<!--        >-->
<!--    </Head>-->

<!--    <section className="p-6 bg-gray-200">-->
<!--        <header className="flex justify-between">-->
<!--            <div className="flex items-center">-->
<!--                <h1 className="font-bold text-lg">-->
<!--                    My App-->
<!--                </h1>-->

<!--                <p className="text-sm ml-4">-->
<!--                    Welcome Back, {{ username }}!-->
<!--                </p>-->
<!--            </div>-->


            <Nav :username="username"></Nav>

            
<!--        </header>-->
<!--    </section>-->

<!--    <section className="p-6">-->
<!--        <div className="max-w-3xl mx-auto">-->
<!--            <slot/>-->
<!--        </div>-->
<!--    </section>-->

<!--    <div class="md:flex md:flex-grow md:overflow-hidden">-->
<!--    <div style="margin-top: 104px;">-->
    <div>
        <!--          <main-menu class="hidden md:block bg-indigo-800 flex-shrink-0 w-56 p-12 overflow-y-auto" />-->
<!--        <div class="md:flex-1 px-4 py-8 md:p-12 md:overflow-y-auto" scroll-region>-->
        <div scroll-region>

            <div v-if="$page.props.flash.success" class="alert alert-success">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.failure" class="alert alert-danger">
                {{ $page.props.flash.failure }}
            </div>
            <slot />
        </div>
    </div>

<!--    <Footer/>-->

</template>

<script>
import Nav from "./Nav";
import Footer from "./Footer";
import FlashMessages from '../Shared/Forms/FlashMessages'

export default {
    components: {
        Nav,
        Footer,
        FlashMessages,
    },

    computed: {
        username() {
            return this.$page.props.username;
        }
    }
};
</script>
