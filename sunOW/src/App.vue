<template>
    <a-config-provider
        :theme="{ token: { 
            colorBgBase:'#000',
            colorTextBase: '#fff',
            colorPrimaryBg:'#000'
        }}"
    >
        <nav>
            <navApp></navApp>
        </nav>
        <router-view v-slot="{ Component,route }">
            <transition name="fade">
                <keep-alive>
                    <component :is="Component" :key="route.path"/>
                </keep-alive>
            </transition>
        </router-view>
        <footer>
            <footerApp></footerApp>
        </footer>
    </a-config-provider>
</template>
<script>
    import footerApp from '@/components/App/footer.vue'
    import navApp from '@/components/App/nav.vue'
export default {
    components:{
        footerApp,navApp
    },
    name: 'process',
    props: {
        msg: String
    },
    
    created(){
        
    },
    data() {
        return {
            
        }
    },
    
}
</script>
<style lang="scss">
#app {
    font-family: Roboto, Roboto-500;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: black;
    width: 100%;
    overflow-x: hidden;
    @include responseTo(mobile){
        overflow-y: auto;
        position: relative;
    }
    
}
$base-font-size: 16px; 
html {
    transition: font-size 0.5s ease-in-out;
    font-size: $base-font-size;
    background-color: #000;
    /* 设置根元素字体大小 */
    @include responseTo(mobile){
        font-size: 12px;
    }
}
nav{
    position: sticky;
    top: 0;
    z-index: 1;
    width: 100%;
    @include responseTo(mobile){
        position: fixed;
        top: 0;
        z-index: 1;
    }
}


a {
    text-decoration: none;
}
</style>
