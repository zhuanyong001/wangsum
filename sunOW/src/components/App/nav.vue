<template>
    <div class="nav">
        <div class="logo" @click="$router.push('/')"></div>
        
        <div class="select">
            <div class="select-meau-btn" @click="toggleMenu" v-click-outside="()=>menuVisible = true">
                <MenuUnfoldOutlined style="font-size: 22px;"/>
            </div>
            <ul :class="['select-meau',{'novisible':menuVisible}]">
                <li v-for="(item,index) in navTo" :key="index" class="a curpo" @click="handleNavigation(item.active)">
                    {{ item.label }}
                </li>
            </ul>
            <div class="custom-select">

                <div class="selected-item" @click="toggleDropdown" v-click-outside="()=>isDropdownVisible = false">
                    
                    <img src="@/assets/images/languages2x.png" alt="">
                    <div class="text">{{ i18nText }}</div>
                    
                </div>
                <ul class="select-list" v-show="isDropdownVisible">
                    <li 
                        v-for="(item, index) in localeList" 
                        :key="index" 
                        @click="handleChange(item.event)" 
                       
                    >
                        {{ item.text }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { Icon } from 'vant';
import { ref,computed, unref } from 'vue'
import tool from '@/utils/tools/tool'
import { useLocale } from '@/locales/useLocale'
import { localeList } from '@/settings/localeSetting'
import { isI18n, geti18n, seti18n, cleari18n } from '@/utils/i18n'
import { useI18n } from 'vue-i18n'
import {
    MenuUnfoldOutlined
} from '@ant-design/icons-vue';
const { t } = useI18n() 
const { changeLocale, getLocale } = useLocale()
async function toggleLocale(lang) {
  await changeLocale(lang)
  location.reload()
}

function handleChange(e) {
    isDropdownVisible.value = false
    if (unref(getLocale) === e) {
        return
    }
    toggleLocale(e)
    
}


const value1 = ref(geti18n())
const navTo = [
    {
        label: t('nav.home_page'),
        active: 'home'
    },
    {
        label: t('nav.about_platform'), // 使用 t 函数进行国际化
        active: 'concise'
    },
    {
        label: t('nav.team_members'), // 假设你在 zh_CN.json 中添加了这个键
        active: 'JoinUsmy'
    },
    {
        label: t('nav.product_services'), // 假设你在 zh_CN.json 中添加了这个键
        active: 'ProductService'
    },
    {
        label: t('nav.operating_principle'), // 假设你在 zh_CN.json 中添加了这个键
        active: 'OperatingPrinciple'
    },
    {
        label: t('nav.dgfy_token'), // 假设你在 zh_CN.json 中添加了这个键
        active: 'DGFYToken'
    },
    {
        label: t('nav.development_planning'), // 假设你在 zh_CN.json 中添加了这个键
        active: 'DevelopmentPlanning'
    },
    {
        label: t('nav.cooperative_partner'), // 假设你在 zh_CN.json 中添加了这个键
        active: 'CooperativePartner'
    }
]
const handleNavigation = (active) =>{
    tool.scrollToSection(active)
}


const i18nText = computed(()=>{
    return localeList.find(item=>item.event === value1.value)?.text
})
const isDropdownVisible = ref(false)
function toggleDropdown() {
  isDropdownVisible.value = !isDropdownVisible.value
}
const menuVisible = ref(true)

function toggleMenu() {
    menuVisible.value = !menuVisible.value
}
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import "./select.scss";
.nav {
    background-color: transparent;
    padding: 30px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: absolute;
    height: 88px;
    z-index: 1;
    top: 0;
    left: 0;
    width: 100%;
    color: #fff;
    .logo {
        background-image: url('@/assets/images/Group405202x.png');
        background-repeat: no-repeat;
        background-size: contain;
        width: 138px;
        height: 44px;
        object-fit: contain;
        cursor: pointer;
    }
    
    .select {
        @include flex-row(12px);
        .select-meau-btn{
            cursor: pointer;
            @include responseTo(web){
                visibility: hidden;  
            }
            @include responseTo(mobile){
                visibility: visible;
                display: block;
                font-weight: 900;

            }
        }
        .select-meau{
            visibility: visible;
            transition: all 0.3s linear;
            
            @include responseTo(web){
                height: 100%;
                @include flex-row(20px);
                align-items: center;
                font-size: 22px;
                font-weight: 400;
                height: 60px;
            }
            @include responseTo(mobile){
                padding: 20px;
                @include flex-col(20px);
                position: absolute;
                text-align: left;
                width: calc(100% - 60px);
                top: 88px;
                left: 50%;
                transform: translateX(-50%);
                background: #fff;
                color: #000;
                border: #fff 1px solid;
                border-radius: 30px;
                font-weight: 600;
            }
            .a {
                cursor: pointer;
                display: block;
                box-sizing: border-box;
                position: relative;
                line-height: 33px;
                &::before{
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 100%;
                    width: 0%;
                    height: 100%;
                    border-bottom: 2px solid #fff;
                    @include responseTo(mobile){
                        border-bottom: 2px solid #000;
                    }
                    
                    transition: 0.1s all linear;
                }
                &:hover::before{
                    width: 100%;
                    top: 0;
                    left: 0;
                    transition-delay: 0.1s;
                }
                &:hover~::before{
                    left: 0;
                }
                &.router-link-exact-active {
                    border-bottom: 2px solid white;
                }
            }
        }
        .novisible{
            @include responseTo(web){
                
            }
            @include responseTo(mobile){
                width: 0;
                display: none;

            }
        }
    }

}
</style>
  