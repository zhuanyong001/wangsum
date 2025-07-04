<template>
  <a-form layout="horizontal" :labelCol="{ span: 3, offset: 0 }">
    <a-form-item v-for="(value, key) in StatisticalForm" :key="key" :label="translateLabel(key)" :name="key">
      <a-input v-model:value="params.STATISTICS[key]" />
    </a-form-item>
    <a-form-item label="富文本语言选择">
      <a-select ref="select" v-model:value="params.selectLang" style="width: 120px" @change="handleChange">
        <a-select-option v-for="item in i18nList" :value="item.value">{{ item.label }}</a-select-option>
      </a-select>
    </a-form-item>
    <a-form-item label="富文本">
      <div style="border: 1px solid #ccc">
        <wangeditor v-model:value="params.valueHtml"></wangeditor>
      </div>
    </a-form-item>
    <a-form-item :wrapper-col="{ offset: 4, span: 16 }">
      <a-button @click="submit">修改</a-button>
    </a-form-item>
  </a-form>
</template>

<script setup>
  import { ref, shallowRef, onMounted, onBeforeUnmount, computed } from 'vue';
  import UploadImgs from '@/components/UploadImgs/index.vue';
  import { Editor, Toolbar } from '@wangeditor/editor-for-vue';
  import '@wangeditor/editor/dist/css/style.css';
  import { useHttp } from '@/utils/useHttp.js';
  import wangeditor from '@/components/wangeditor/index.vue';
  import { useSelectStore } from '@/store/selectOpts';
  const selectStore = useSelectStore();
  const i18nList = computed(() => selectStore.i18nList);
  // 定义响应式数据
  const params = ref({
    img: '',
    valueHtml: '',
    selectLang: 'en',
  });
  // 表单内容
  const StatisticalForm = ref({});

  const handleChange = (e) => {
    const lang = params.value.selectLang === 'zh_CN' ? '' : '_' + params.value.selectLang;
    getRichText(e).then((res) => {
      params.value.valueHtml = res.data[`INCREASE_REVENUE_RICH_TEXT${lang}`];
    });
  };

  // 键名与翻译之间的映射对象
  const translations = {
    TOTAL_PLEDGE_VALUE: '总质押价值',
    STABLE_COIN_PLEDGE_VALUE: '稳定币质押价值',
    DRAGONFLYSWAP_PLEDGE_VALUE: 'DragonflySwap 质押价值',
    PLEDGE_VALUE_OF_MINING_POOL: '矿池质押价值',
    DATA4:'流动性挖矿价值',
    DATA5:'质押挖矿价值',
    DATA6:'矿池存款价值'
  };
  // 模拟异步获取内容
  onMounted(async () => {
    try {
      const res = await getConfig();
      params.value.img = res.data.LOGO_IMG;
      params.value.valueHtml = res.data.INCREASE_REVENUE_RICH_TEXT;
      StatisticalForm.value = JSON.parse(res.data['HOME_DATA_STATISTICS']);
      params.value.STATISTICS = StatisticalForm.value;
    } catch (error) {
      console.error('获取数据失败:', error);
    }
  });
  // API 请求函数
  const getConfig = () => {
    return useHttp('/web3/system_configs/get_configs', 'POST_JSON', {
      names: ['LOGO_IMG', 'FOOTER_IMAGE', 'INCREASE_REVENUE_RICH_TEXT', 'HOME_DATA_STATISTICS'],
    });
  };
  const getRichText = (lang) => {
    let names = [`INCREASE_REVENUE_RICH_TEXT`];
    if (lang !== 'zh_CN') {
      names[0] = names[0] + '_' + lang;
    }
    return useHttp('/web3/system_configs/get_configs', 'POST_JSON', {
      names,
    });
  };
  const setImgLogo = (data) => {
    return useHttp('/web3/system_configs/update_configs', 'POST_JSON', data);
  };

  // 提交表单
  const submit = async () => {
    const lang = params.value.selectLang === 'zh_CN' ? '' : '_' + params.value.selectLang;
    try {
      const form = {
        configs: [
          {
            name: `INCREASE_REVENUE_RICH_TEXT${lang}`,
            value: params.value.valueHtml,
          },
          {
            name: `HOME_DATA_STATISTICS`,
            value: JSON.stringify(params.value.STATISTICS),
          },
        ],
      };
      await setImgLogo(form);
      // 提交成功后的处理逻辑，例如提示用户
      console.log('提交成功');
    } catch (error) {
      console.error('提交失败:', error);
    }
  };

  // 使用翻译对象返回翻译后的标签
  const translateLabel = (key) => {
    return translations[key] || key;
  };
</script>
