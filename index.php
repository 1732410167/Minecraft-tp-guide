<?php
require 'functions.php';
$items = loadItems();
$tags = getUniqueTags($items);
$editMode = isset($_POST['editMode']) ? $_POST['editMode'] : 'false';

// 表单
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['saveItem'])) {
        saveItem($_POST);
        header('Location: index.php');
        exit;
    } elseif (isset($_POST['deleteItem'])) {
        deleteItem($_POST['itemId']);
        header('Location: index.php');
        exit;
    } elseif (isset($_POST['toggleEdit'])) {
        $editMode = $editMode === 'true' ? 'false' : 'true';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>柚子世界-传送导航</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        neutral: '#64748B',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .card-shadow {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }
            .card-hover {
                transition: all 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }
        }
    </style>
<style type="text/tailwindcss">
    @layer utilities {
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .bg-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    }

    
    /* 字体 */
    @font-face {
        font-family: 'CustomFont';
        src: url('/tptool/assets/fonts/custom-font.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }

    body {
        font-family: 'CustomFont', sans-serif; 
    }

</style>
</head>
<body class="min-h-screen" style="background-image: url('https://ciallo.iepose.cn/tptool/assets/images/bg.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <div class="container mx-auto px-4 py-8">
       
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-[clamp(1.8rem,4vw,2.5rem)] font-bold text-gray-800">Ciallo～ (∠・ω< )⌒★,你要去哪里喵？</h1>
            
            <div class="flex flex-wrap gap-2 mb-4">
                <button class="filter-tag bg-primary text-white px-3 py-1 rounded-full text-sm active" data-tag="all">
                    全部
                </button>
                <?php foreach ($tags as $tag): ?>
                    <button class="filter-tag bg-gray-200 hover:bg-primary hover:text-white px-3 py-1 rounded-full text-sm transition-colors" data-tag="<?= htmlspecialchars($tag) ?>">
                        <?= htmlspecialchars($tag) ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <form method="post" class="ml-auto">
                <button type="submit" name="toggleEdit" class="bg-<?= $editMode === 'true' ? 'red-500' : 'primary' ?> text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                    <i class="fa <?= $editMode === 'true' ? 'fa-save' : 'fa-edit' ?> mr-1"></i>
                    <?= $editMode === 'true' ? '保存' : '编辑' ?>
                </button>
            </form>
        </header>
        

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($items as $item): ?>
                <div class="card" data-tags="<?= htmlspecialchars(implode(',', $item['tags'])) ?>">
                    <div class="card-inner bg-white rounded-xl overflow-hidden card-shadow card-hover h-full">
                        <div class="card-view">
                            <div class="relative">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" 
                                     class="w-full h-48 object-cover">
                                <?php if ($editMode === 'true'): ?>
                                    <button class="edit-item absolute top-2 right-2 bg-white/80 p-2 rounded-full hover:bg-white transition-colors"
                                            data-id="<?= htmlspecialchars($item['id']) ?>">
                                        <i class="fa fa-pencil text-gray-700"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= htmlspecialchars($item['title']) ?></h3>
                            </div>
                        </div>
                        

                        <div class="detail-view hidden p-4 h-full flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4"><?= htmlspecialchars($item['title']) ?></h3>
                            <div class="flex-grow flex flex-col gap-3">
                                <?php foreach ($item['buttons'] as $button): ?>
                                    <button class="copy-btn bg-primary hover:bg-primary/90 text-white py-2 px-4 rounded-lg transition-colors"
                                            data-text="<?= htmlspecialchars($button['text']) ?>">
                                        <?= htmlspecialchars($button['label']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <button class="back-btn mt-4 text-gray-600 hover:text-gray-800 text-sm">
                                <i class="fa fa-arrow-left mr-1"></i> 返回
                            </button>
                        </div>
                        

                        <div class="copy-success hidden absolute inset-0 bg-black/70 flex items-center justify-center text-white text-lg font-medium">
                            复制成功!
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            

            <?php if ($editMode === 'true'): ?>
                <div class="flex items-center justify-center">
                    <button id="addNewCard" class="w-full h-full border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition-colors p-4">
                        <i class="fa fa-plus text-3xl mb-2"></i>
                        <span>添加新卡片</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    

    <?php include 'edit-modal.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const cardViews = document.querySelectorAll('.card-view');
            cardViews.forEach(view => {
                view.addEventListener('click', function(e) {
  
                    if (e.target.closest('.edit-item')) return;
                    
                    const cardInner = this.closest('.card-inner');
                    this.classList.add('hidden');
                    cardInner.querySelector('.detail-view').classList.remove('hidden');
                });
            });
            

            const backBtns = document.querySelectorAll('.back-btn');
            backBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const cardInner = this.closest('.card-inner');
                    cardInner.querySelector('.detail-view').classList.add('hidden');
                    cardInner.querySelector('.card-view').classList.remove('hidden');
                });
            });
            
            // 复制
            const copyBtns = document.querySelectorAll('.copy-btn');
            copyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const textToCopy = this.getAttribute('data-text');
                    const cardInner = this.closest('.card-inner');
                    const successMsg = cardInner.querySelector('.copy-success');
                    
                    navigator.clipboard.writeText(textToCopy).then(() => {

                        successMsg.classList.remove('hidden');
                        

                        setTimeout(() => {
                            successMsg.classList.add('hidden');
                            cardInner.querySelector('.detail-view').classList.add('hidden');
                            cardInner.querySelector('.card-view').classList.remove('hidden');
                        }, 1500);
                    }).catch(err => {
                        console.error('复制失败: ', err);
                    });
                });
            });
            
            // 筛选逻辑
            const filterTags = document.querySelectorAll('.filter-tag');
            const cards = document.querySelectorAll('.card');
            
            filterTags.forEach(tag => {
                tag.addEventListener('click', function() {

                    filterTags.forEach(t => t.classList.remove('active', 'bg-primary', 'text-white'));
                    filterTags.forEach(t => t.classList.add('bg-gray-200', 'hover:bg-primary', 'hover:text-white'));
                    this.classList.add('active', 'bg-primary', 'text-white');
                    this.classList.remove('bg-gray-200', 'hover:bg-primary', 'hover:text-white');
                    
                    const selectedTag = this.getAttribute('data-tag');
                    
                    cards.forEach(card => {
                        if (selectedTag === 'all' || card.getAttribute('data-tags').includes(selectedTag)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
            
            // 编辑模态框控制
            const editModal = document.getElementById('editModal');
            const closeModal = document.getElementById('closeModal');
            const addNewCard = document.getElementById('addNewCard');
            const editForm = document.getElementById('editForm');
            const deleteBtn = document.getElementById('deleteBtn');
            const itemIdInput = document.getElementById('itemId');
            const addButton = document.getElementById('addButton');
            const buttonsContainer = document.getElementById('buttonsContainer');
            
            // 打开添加新卡片模态框
            if (addNewCard) {
                addNewCard.addEventListener('click', function() {
                    editForm.reset();
                    itemIdInput.value = '';
                    deleteBtn.classList.add('hidden');
                    document.getElementById('modalTitle').textContent = '添加新卡片';
                    buttonsContainer.innerHTML = `
                        <div class="button-group mb-3">
                            <input type="text" name="buttonLabels[]" placeholder="按钮标签" class="w-full p-2 border border-gray-300 rounded mb-2">
                            <textarea name="buttonTexts[]" placeholder="要复制的内容" class="w-full p-2 border border-gray-300 rounded"></textarea>
                        </div>
                    `;
                    editModal.classList.remove('hidden');
                });
            }
            
            // 打开编辑卡片模态框
            const editButtons = document.querySelectorAll('.edit-item');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    fetch('functions.php?action=getItem&id=' + itemId)
                        .then(response => response.json())
                        .then(item => {
                            if (item) {
                                itemIdInput.value = item.id;
                                document.getElementById('title').value = item.title;
                                document.getElementById('image').value = item.image;
                                document.getElementById('tags').value = item.tags.join(', ');
                                
                                // 填充按钮数据
                                buttonsContainer.innerHTML = '';
                                item.buttons.forEach(button => {
                                    const buttonGroup = document.createElement('div');
                                    buttonGroup.className = 'button-group mb-3';
                                    buttonGroup.innerHTML = `
                                        <input type="text" name="buttonLabels[]" value="${button.label}" placeholder="按钮标签" class="w-full p-2 border border-gray-300 rounded mb-2">
                                        <textarea name="buttonTexts[]" placeholder="要复制的内容" class="w-full p-2 border border-gray-300 rounded">${button.text}</textarea>
                                    `;
                                    buttonsContainer.appendChild(buttonGroup);
                                });
                                
                                deleteBtn.classList.remove('hidden');
                                document.getElementById('modalTitle').textContent = '编辑卡片';
                                editModal.classList.remove('hidden');
                            }
                        });
                });
            });
            
            // 关闭模态框
            closeModal.addEventListener('click', function() {
                editModal.classList.add('hidden');
            });
            
            // 点击模态框外部关闭
            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });
            
            // 添加更多按钮
            addButton.addEventListener('click', function() {
                const buttonGroup = document.createElement('div');
                buttonGroup.className = 'button-group mb-3';
                buttonGroup.innerHTML = `
                    <input type="text" name="buttonLabels[]" placeholder="按钮标签" class="w-full p-2 border border-gray-300 rounded mb-2">
                    <textarea name="buttonTexts[]" placeholder="要复制的内容" class="w-full p-2 border border-gray-300 rounded"></textarea>
                `;
                buttonsContainer.appendChild(buttonGroup);
            });
            
            // 删除
            deleteBtn.addEventListener('click', function() {
                if (confirm('确定要删除这个卡片吗？')) {
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    </script>
</body>
</html>
