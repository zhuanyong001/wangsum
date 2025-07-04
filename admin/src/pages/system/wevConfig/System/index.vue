<template>
  <div>
    <a-form :model="form" :label-col="{ span: 4 }" :wrapper-col="{ span: 14 }" labelWrap>
      <template v-for="item in JsonForm" :key="item.key">
        <JsomFormItem :item="item" v-model:modelValue="form[item.key]" />
      </template>
      <a-form-item :wrapper-col="{ offset: 4, span: 16 }">
        <a-button type="primary" @click="submit">保存</a-button>
      </a-form-item>
    </a-form>
  </div>
</template>
<script setup>
  import JsomFormItem from '@/components/dy/JsonFormItem/index.vue';
  const form = ref({});
  const props = defineProps({
    type: [Number, String],
  });
  const id = ref(0);
  const JsonForm = ref([]);
  const getData = () => {
    useGet('/web3/system_configs/get_configs_by_category', {
      category: props.type,
    })
      .then((res) => {
        if (res.code == 200) {
          DataToFromJson(res.data);
        }
      })
      .catch((err) => {});
  };
  /*
   * DATA TO FORMJSON
   */
  const DataToFromJson = (data) => {
    let formJson = {};
    data.forEach((item) => {
      let type = 'text';
      if (item.type == 'string') {
        type = 'text';
      } else if (item.type == 'number') {
        type = 'number';
      } else if (item.type == 'boolean') {
        type = 'switch';
      } else if (item.type == 'json') {
        type = 'textarea';
      } else if (item.type == 'image') {
        type = 'uploadimg';
      }
      formJson[item.name] = {
        label: item.description,
        key: item.name,
        type: type,
        //  desc: item.description,
      };
      form.value[item.name] = item.value;
    });
    JsonForm.value = formJson;
  };

  const submit = () => {
    const configs = Object.keys(form.value).map((key) => {
      return {
        name: key,
        value: form.value[key],
      };
    });

    usePost('/web3/system_configs/update_configs', {
      configs,
    })
      .then((res) => {
        if (res.code == 200) {
          message.success('保存成功');
        } else {
          message.error(res.msg);
        }
      })
      .catch((err) => {
        //  loading.value = false;
      });
  };
  getData();
</script>
<style lang="less" scoped>
  .desc {
    color: #999;
    margin-top: 5px;
    font-size: 12px;
    line-height: 1.5;
    margin-bottom: 0;
    display: block;
    margin-left: 4px;
    margin-top: 4px;
    margin-bottom: 4px;
    font-size: 14px;
  }
</style>
