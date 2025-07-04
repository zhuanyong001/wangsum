<template>
    <div>
        <a-form :model="form" :label-col="{ span: 4 }" :wrapper-col="{ span: 14 }" labelWrap>
            <a-form-item label="富文本语言选择">
                <a-select ref="select" v-model:value="form.selectLang" style="width: 120px" @change="handleChange">
                    <a-select-option v-for="item in i18nList" :value="item.value">{{ item.label }}</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item label="滚动公告语言选择">
                <a-textarea v-model:value="params[form['selectLang']]" placeholder="Basic usage" :rows="4" />
            </a-form-item>

            <a-form-item :wrapper-col="{ offset: 4, span: 16 }">
                <a-button type="primary" @click="submit">保存</a-button>
            </a-form-item>
        </a-form>
    </div>
</template>
<script setup>
import JsomFormItem from '@/components/dy/JsonFormItem/index.vue';
import { useSelectStore } from '@/store/selectOpts';
const selectStore = useSelectStore();
const i18nList = computed(() => selectStore.i18nList);
const form = ref({
    selectLang: i18nList.value[0]?.value
});
const params = ref({
    "id": 999,
    "page": "index",
    "i18nkey": "notice",
    "zh_tw": "",
    "ko": "",
    "jp": "",
    "en": "",
    "zh_CN": ""
})
const getData = () => {
    usePost('/web3/system_configs/get_configs', {
        names: ['SCROLL_NOTICE'],
    })
    .then((res) => {
        const [page,key] = res.data['SCROLL_NOTICE'].split('.')
        return usePost('/i18n/list', {
            page,
            key
        })
    }).then((res) => {
        if(res.list.length){
            params.value = res.list[0]
        }
    })
    
};
getData()
const handleChange = () => {
    
}
const submit = () => {
    usePost('/i18n/save', params.value)
    .then((res) => {
        // getData()
    })
};
</script>
<style lang="less" scoped></style>