export const useObserver = (
    callBack,
    isLoading,
    endStatus
    ) => {
    const ob = new IntersectionObserver(
        function(entries){
            const [entry] = entries
            if(entry.isIntersecting && !isLoading.value && !endStatus.value){
                callBack()
            }
        },
        {
            root:null,
            threshold:0.1
        }
    )
    return { ob }
}