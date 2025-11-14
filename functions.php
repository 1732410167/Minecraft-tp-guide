<?php
// 确保data目录存在
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// 确保XML文件存在并初始化
function initXmlFile() {
    $file = 'data/items.xml';
    if (!file_exists($file)) {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        $root = $xml->createElement('items');
        $xml->appendChild($root);
        $xml->save($file);
    }
}

// 加载所有项目
function loadItems() {
    initXmlFile();
    $file = 'data/items.xml';
    $xml = simplexml_load_file($file);
    $items = [];
    
    foreach ($xml->item as $item) {
        $buttons = [];
        foreach ($item->buttons->button as $button) {
            $buttons[] = [
                'label' => (string)$button['label'],
                'text' => (string)$button
            ];
        }
        
        $tags = [];
        if (isset($item->tags)) {
            $tags = explode(',', (string)$item->tags);
            $tags = array_map('trim', $tags);
        }
        
        $items[] = [
            'id' => (string)$item['id'],
            'title' => (string)$item->title,
            'image' => (string)$item->image,
            'tags' => $tags,
            'buttons' => $buttons
        ];
    }
    
    return $items;
}

// 获取所有唯一标签
function getUniqueTags($items) {
    $tags = [];
    foreach ($items as $item) {
        foreach ($item['tags'] as $tag) {
            if (!empty($tag) && !in_array($tag, $tags)) {
                $tags[] = $tag;
            }
        }
    }
    sort($tags);
    return $tags;
}

// 保存项目
function saveItem($data) {
    initXmlFile();
    $file = 'data/items.xml';
    $xml = simplexml_load_file($file);
    
    // 处理标签
    $tags = isset($data['tags']) ? explode(',', $data['tags']) : [];
    $tags = array_map('trim', $tags);
    $tagsStr = implode(',', $tags);
    
    // 处理按钮
    $buttonLabels = isset($data['buttonLabels']) ? $data['buttonLabels'] : [];
    $buttonTexts = isset($data['buttonTexts']) ? $data['buttonTexts'] : [];
    $buttons = [];
    for ($i = 0; $i < count($buttonLabels); $i++) {
        if (!empty($buttonLabels[$i]) || !empty($buttonTexts[$i])) {
            $buttons[] = [
                'label' => $buttonLabels[$i],
                'text' => $buttonTexts[$i]
            ];
        }
    }
    
    if (!empty($data['itemId'])) {
        // 更新现有项目
        $itemId = $data['itemId'];
        foreach ($xml->item as $item) {
            if ((string)$item['id'] == $itemId) {
                $item->title = $data['title'];
                $item->image = $data['image'];
                $item->tags = $tagsStr;
                
                // 清空现有按钮
                unset($item->buttons);
                $buttonsNode = $item->addChild('buttons');
                
                // 添加新按钮
                foreach ($buttons as $btn) {
                    $buttonNode = $buttonsNode->addChild('button', htmlspecialchars($btn['text']));
                    $buttonNode->addAttribute('label', htmlspecialchars($btn['label']));
                }
                break;
            }
        }
    } else {
        // 添加新项目
        $itemId = uniqid();
        $item = $xml->addChild('item');
        $item->addAttribute('id', $itemId);
        $item->addChild('title', htmlspecialchars($data['title']));
        $item->addChild('image', htmlspecialchars($data['image']));
        $item->addChild('tags', $tagsStr);
        
        $buttonsNode = $item->addChild('buttons');
        foreach ($buttons as $btn) {
            $buttonNode = $buttonsNode->addChild('button', htmlspecialchars($btn['text']));
            $buttonNode->addAttribute('label', htmlspecialchars($btn['label']));
        }
    }
    
    $xml->asXML($file);
}

// 删除项目
function deleteItem($itemId) {
    initXmlFile();
    $file = 'data/items.xml';
    $xml = simplexml_load_file($file);
    
    foreach ($xml->item as $index => $item) {
        if ((string)$item['id'] == $itemId) {
            unset($xml->item[$index]);
            break;
        }
    }
    
    $xml->asXML($file);
}

// 处理获取单个项目的请求
if (isset($_GET['action']) && $_GET['action'] == 'getItem' && isset($_GET['id'])) {
    $items = loadItems();
    $itemId = $_GET['id'];
    $result = null;
    
    foreach ($items as $item) {
        if ($item['id'] == $itemId) {
            $result = $item;
            break;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>