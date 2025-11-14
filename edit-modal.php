<!-- 背景 -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <!-- 内容 -->
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-800">编辑卡片</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- 编辑 -->
            <form id="editForm" method="post">
                <input type="hidden" id="itemId" name="itemId" value="">
                
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 mb-1">标题</label>
                    <input type="text" id="title" name="title" required
                           class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 mb-1">图片URL</label>
                    <input type="text" id="image" name="image" required
                           class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-sm text-gray-500 mt-1">可以使用在线图片URL或本地图片路径</p>
                </div>
                
                <div class="mb-4">
                    <label for="tags" class="block text-gray-700 mb-1">标签 (用逗号分隔)</label>
                    <input type="text" id="tags" name="tags"
                           class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-sm text-gray-500 mt-1">标签用于筛选，不在前台显示</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">按钮设置</label>
                    <div id="buttonsContainer" class="mb-2">
                    </div>
                    <button type="button" id="addButton" class="text-primary hover:text-primary/80 text-sm">
                        <i class="fa fa-plus mr-1"></i> 添加按钮
                    </button>
                </div>
                
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                        取消
                    </button>
                    <button type="submit" name="saveItem" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 transition-colors">
                        保存
                    </button>
                </div>
            </form>
            
            <!-- 删除 -->
            <form id="deleteForm" method="post" class="mt-4">
                <input type="hidden" name="itemId" value="">
                <button type="submit" name="deleteItem" id="deleteBtn" class="hidden w-full py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                    <i class="fa fa-trash mr-1"></i> 删除此卡片
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancelBtn').addEventListener('click', function() {
        document.getElementById('editModal').classList.add('hidden');
    });
    
    document.getElementById('itemId').addEventListener('change', function() {
        document.querySelector('#deleteForm input[name="itemId"]').value = this.value;
    });
</script>