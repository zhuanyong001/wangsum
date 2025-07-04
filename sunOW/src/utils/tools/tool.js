import { nextTick } from "vue";

const tool = {
    componentInstance:null,
    handleNavigation(componentInstance,param){
        if (!param) {
            return;
        }
        this.componentInstance = componentInstance
        const parts = param.split('#');
        componentInstance.$router.push({
            path: `/${parts[0]}`
        });
        
        // componentInstance.$nextTick(()=>{
        //     this.scrollToSection(parts[1]);
        // })
        setTimeout(()=>{
            this.scrollToSection(parts[1]);
        },100);
    },
    scrollToSection(element) {
        var section = document.getElementById(element);
        console.log('section',section);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    },
  }
  export default tool;

