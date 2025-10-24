<?php

use App\Http\Controllers\ChartDesigner;
use App\Models\LinkChartEmbed;
use App\Models\SlinkChartEmbed;
use App\Models\CLinkChartEmbed;
use App\Models\DotLinkEmbedChart;
use Livewire\Volt\Component;

new class extends Component {
    public $bagan_id;
    public $bagan_name;
    public $chartOptions = [];
    public $cardOptions = [];
    public $typeOptions = [];
    public $nodes = [];
    public $slinks = [];
    public $clinks = [];
    public $dotlinks = [];
    public $sublevels = [];

    public function mount($idData = null)
    {
        $this->bagan_id = $idData;
        $this->bagan_name = optional(\App\Models\BaganList::find($this->bagan_id))->name;
        $this->chartOptions = ChartDesigner::DefaultChartOption();
        $this->loadChartData();
    }

    public function loadChartData()
    {
        if ($this->bagan_id) {
            $this->nodes = LinkChartEmbed::with(["getSubLevel","getTemplateBagan","getNodeType","user"])->where('bagan_list_id', $this->bagan_id)->get()->toArray();



            $this->slinks = SlinkChartEmbed::where('bagan_id', $this->bagan_id)->get()->toArray();

            $this->clinks = CLinkChartEmbed::where('bagan_id', $this->bagan_id)->get()->toArray();

            $this->dotlinks = DotLinkEmbedChart::where('bagan_id', $this->bagan_id)->get()->toArray();

            $this->cardOptions = \App\Models\templateBagan::where('type','card')->get()->toArray();
            $this->typeOptions = \App\Models\templateBagan::where('type','type')->get()->toArray();

            // Load sublevels
            $this->sublevels = \App\Models\SubLevels::where('bagan_list_id', $this->bagan_id)->get()->toArray();
        }
    }

    // Listen for refresh events
    #[\Livewire\Attributes\On('refreshChart')]
    public function refreshChart()
    {
        $this->loadChartData();
    }
}; ?>

<div>
    <!-- Loading Progress Indicator -->
    <div id="chartLoadingOverlay" style="display: none; position: relative; width: 100%; background: #f3f4f6; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
        <div class="text-center">
            <div class="mb-2">
                <svg class="animate-spin h-8 w-8 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800" id="loadingTitle">Loading Chart...</h3>
            <p class="text-sm text-gray-600 mt-1" id="loadingMessage">Preparing data...</p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div id="loadingProgressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2" id="loadingPercentage">0%</p>
        </div>
    </div>

    <div id="TreeOrganizationChartDiv" style="width: 100%; height: 600px;"></div>
</div>

@assets
<script src="{{asset('js/orgchart.js')}}"></script>
@endassets

@script
<script>
    (function() {
        let chartInstance = null;

        // Function to initialize chart
        function initializeChart() {
            console.log('Initializing chart...');

            // Destroy existing chart instance if any
            if (chartInstance) {
                try {
                    // Clear the chart div
                    const chartDiv = document.getElementById("TreeOrganizationChartDiv");
                    if (chartDiv) {
                        chartDiv.innerHTML = '';
                    }
                } catch (e) {
                    console.log('Error clearing chart:', e);
                }
            }

            function getOptions(){
                const searchParams = new URLSearchParams(window.location.search);
                let fit = searchParams.get('fit');
                let scaleInitial = 1;
                if (fit === 'yes'){
                    scaleInitial = OrgChart.match.boundary;
                }
                return {scaleInitial};
            }

            let screenOption = getOptions();

            const option = @json($chartOptions);
            option.mouseScrool = OrgChart.action.scroll;
            option.scaleInitial = OrgChart.match.boundary;

            // Initialize tags object if it doesn't exist
            if (!option.tags) {
                option.tags = {};
            }

            const templateDefinedList = @json($cardOptions) || [];

            // Add card templates
            if (templateDefinedList && templateDefinedList.length > 0) {
                templateDefinedList.forEach(function(template) {
                    if (template.name && template.template && template.template.indexOf('group') === 0) {
                        // Parse group-1 to ['group','1']
                        const parsedName = template.template.split('-');

                        option.tags[template.name] = {
                            template: parsedName[0],
                            subTreeConfig: {
                                siblingSeparation: 3,
                                columns: parsedName[1]
                            }
                        };
                    } else if (template.name && template.template) {
                        option.tags[template.name] = {
                            template: template.template,
                        };
                    }
                });
            }

            const sublevels = @json($sublevels) || [];

            // Add sublevel configurations
            if (sublevels && sublevels.length > 0) {
                console.log('Sublevels:', sublevels);
                sublevels.forEach(function(sublevel) {
                    if (sublevel.name && sublevel.value) {
                        option.tags[sublevel.name] = {
                            subLevels: parseInt(sublevel.value, 10)
                        };
                    }
                });
            }

            // Custom Department Banner Templates
            // Template 1: Banner Departemen Biru
            OrgChart.templates['departemen-banner-biru'] = Object.assign({}, OrgChart.templates.ana);
            OrgChart.templates['departemen-banner-biru'].size = [250, 50];
            OrgChart.templates['departemen-banner-biru'].node =
                '<rect x="0" y="0" width="250" height="50" fill="#1e40af" stroke="#1e3a8a" stroke-width="2" rx="8"></rect>' +
                '<rect x="6" y="6" width="238" height="38" fill="#3b82f6" rx="5"></rect>';
            OrgChart.templates['departemen-banner-biru'].field_0 =
                '<text style="font-size: 18px; font-weight: bold;" fill="#ffffff" text-anchor="middle" x="125" y="32">{val}</text>';

            // Template 2: Banner Departemen Hijau
            OrgChart.templates['departemen-banner-hijau'] = Object.assign({}, OrgChart.templates.ana);
            OrgChart.templates['departemen-banner-hijau'].size = [250, 50];
            OrgChart.templates['departemen-banner-hijau'].node =
                '<rect x="0" y="0" width="250" height="50" fill="#15803d" stroke="#166534" stroke-width="2" rx="8"></rect>' +
                '<rect x="6" y="6" width="238" height="38" fill="#22c55e" rx="5"></rect>';
            OrgChart.templates['departemen-banner-hijau'].field_0 =
                '<text style="font-size: 18px; font-weight: bold;" fill="#ffffff" text-anchor="middle" x="125" y="32">{val}</text>';

            // Template 3: Banner Departemen Merah
            OrgChart.templates['departemen-banner-merah'] = Object.assign({}, OrgChart.templates.ana);
            OrgChart.templates['departemen-banner-merah'].size = [250, 50];
            OrgChart.templates['departemen-banner-merah'].node =
                '<rect x="0" y="0" width="250" height="50" fill="#b91c1c" stroke="#991b1b" stroke-width="2" rx="8"></rect>' +
                '<rect x="6" y="6" width="238" height="38" fill="#ef4444" rx="5"></rect>';
            OrgChart.templates['departemen-banner-merah'].field_0 =
                '<text style="font-size: 18px; font-weight: bold;" fill="#ffffff" text-anchor="middle" x="125" y="32">{val}</text>';

            option.nodeBinding = {
                img_0: "img",
                field_0: "name",
                field_1: "nik",
                field_2: "team",
                link_field_0: "label",
            };
            option.orientation = OrgChart.orientation.top;

            function pdfPreview(){
                chartInstance.pdfPreviewUI.show({
                    header: '<text>{{$bagan_name}}</text>',
                    footer: '<text>{{$bagan_name}}. Page {current-page} of {total-pages}</text>'
                });
            }

            option.menu = {
                png_preview: { text: 'Preview PNG' },
                svg_preview: { text: 'Preview SVG' },
                pdf_preview: { text: 'export as PDF',onClick: pdfPreview },
            }

            option.controls = {
                pdf_export: { title: 'Export to PDF' },
                zoom_in: { title: "Zoom In"},
                zoom_out: { title: "Zoom Out"},
                fit: { title: "Fit the chart"}
            }
            const dotlinks = @json($dotlinks);
            if (dotlinks && dotlinks.length > 0) {
                option.dottedLines = [];
                dotlinks.forEach(function(dotlink) {
                    console.log(dotlink);
                    option.dottedLines.push({
                        from: dotlink.from,
                        to: dotlink.to,
                        template: dotlink.template,
                        rootId: dotlink.rootId
                    });
                });
            }

            console.log('Chart Options:', option);

            chartInstance = new OrgChart(document.getElementById("TreeOrganizationChartDiv"), option);

            // Load nodes data
            const nodesData = @json($nodes);
            let nodes = [];
            const slinks = @json($slinks);
            const clinks = @json($clinks);
            console.log('Loading chart data...', nodesData);

            // Cache for base64 images to avoid re-converting
            const imageCache = new Map();

            // Function to update loading progress
            function updateLoadingProgress(current, total, message = '') {
                const overlay = document.getElementById('chartLoadingOverlay');
                const progressBar = document.getElementById('loadingProgressBar');
                const percentage = document.getElementById('loadingPercentage');
                const loadingMessage = document.getElementById('loadingMessage');

                if (overlay && progressBar && percentage) {
                    overlay.style.display = 'block';
                    const percent = Math.round((current / total) * 100);
                    progressBar.style.width = percent + '%';
                    percentage.textContent = percent + '%';

                    if (message) {
                        loadingMessage.textContent = message;
                    }
                }
            }

            // Function to hide loading progress
            function hideLoadingProgress() {
                const overlay = document.getElementById('chartLoadingOverlay');
                if (overlay) {
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 500);
                }
            }

            // Optimized function to convert image to base64 with compression
            function imageToBase64(url) {
                // Return cached version if available
                if (imageCache.has(url)) {
                    return Promise.resolve(imageCache.get(url));
                }

                return new Promise((resolve) => {
                    const img = new Image();
                    img.crossOrigin = 'Anonymous';

                    img.onload = function() {
                        try {
                            const canvas = document.createElement('canvas');

                            // Optimize size - resize large images to max 150x150
                            const maxSize = 150;
                            let width = img.width;
                            let height = img.height;

                            if (width > maxSize || height > maxSize) {
                                const ratio = Math.min(maxSize / width, maxSize / height);
                                width = Math.floor(width * ratio);
                                height = Math.floor(height * ratio);
                            }

                            canvas.width = width;
                            canvas.height = height;

                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            // Use JPEG with 80% quality for smaller file size
                            const dataURL = canvas.toDataURL('image/jpeg', 0.8);

                            // Cache the result
                            imageCache.set(url, dataURL);
                            resolve(dataURL);
                        } catch (error) {
                            console.warn('Failed to convert image:', url, error);
                            resolve(url); // Fallback to URL
                        }
                    };

                    img.onerror = function() {
                        console.warn('Image load error:', url);
                        resolve(url); // Fallback to URL
                    };

                    img.src = url;
                });
            }

            // Process nodes in batches for better performance
            async function processNodesInBatches() {
                const BATCH_SIZE = 10; // Process 10 images at a time
                const processedNodes = [];
                const totalNodes = nodesData.length;

                console.log(`Processing ${totalNodes} nodes in batches of ${BATCH_SIZE}...`);

                // Show initial loading
                updateLoadingProgress(0, totalNodes, `Processing 0 of ${totalNodes} nodes...`);

                for (let i = 0; i < nodesData.length; i += BATCH_SIZE) {
                    const batch = nodesData.slice(i, i + BATCH_SIZE);

                    const batchPromises = batch.map(async (node) => {
                        let temp = {
                            'id': node.chart_id,
                            'tags': [],
                        };

                        if (node.chart_pid) temp.pid = node.chart_pid;
                        if (node.chart_stpid) temp.stpid = node.chart_stpid;
                        if (node.chart_ppid) temp.ppid = node.chart_ppid;
                        if (node.name) temp.name = node.name;

                        if (node.get_template_bagan && node.get_template_bagan.template) {
                            temp.tags.push(node.get_template_bagan.name);
                        }

                        if (node.get_node_type && node.get_node_type.name) {
                            temp.tags.push(node.get_node_type.template);
                        }

                        if (node.get_sub_level && node.get_sub_level.name) {
                            temp.tags.push(node.get_sub_level.name);
                        }

                        if (node.label) temp.label = node.label;
                        if (node.nik) temp.nik = node.nik;
                        if (node.team) temp.team = node.team;
                        if (node.phone) temp.phone = node.phone;

                        // Convert image to base64 data URI
                        if (node.user && node.user.image_path) {
                            const imageUrl = "{{ asset('storage') }}/" + node.user.image_path;
                            temp.img = await imageToBase64(imageUrl);
                        }

                        return temp;
                    });

                    const batchResults = await Promise.all(batchPromises);
                    processedNodes.push(...batchResults);

                    // Update progress after each batch
                    const currentProgress = processedNodes.length;
                    updateLoadingProgress(
                        currentProgress,
                        totalNodes,
                        `Processing ${currentProgress} of ${totalNodes} nodes...`
                    );
                    console.log(`Progress: ${Math.round((currentProgress / totalNodes) * 100)}% (${currentProgress}/${totalNodes})`);

                    // Allow UI to breathe between batches
                    if (i + BATCH_SIZE < nodesData.length) {
                        await new Promise(resolve => setTimeout(resolve, 0));
                    }
                }

                return processedNodes;
            }

            // Load chart with base64 images
            console.log('Converting images to base64...');
            processNodesInBatches().then(processedNodes => {
                nodes = processedNodes;

                // Update to 100% and show completion message
                updateLoadingProgress(nodesData.length, nodesData.length, 'Chart loaded successfully!');

                console.log('All images converted successfully!');
                console.log('Nodes:', nodes);
                console.log('Slinks:', slinks);
                console.log('Clinks:', clinks);
                console.log('Dotlinks:', dotlinks);

                // Load chart data
                chartInstance.load(nodes);

                // Add slinks (second links)
                if (slinks && slinks.length > 0) {
                    slinks.forEach(function(slink) {
                        chartInstance.addSlink(slink.from, slink.to, slink.label, slink.template);
                    });
                }

                // Add clinks (curve links)
                if (clinks && clinks.length > 0) {
                    clinks.forEach(function(clink) {
                        chartInstance.addClink(clink.from, clink.to, clink.label, clink.template);
                    });
                }

                // Hide loading overlay after chart is rendered
                hideLoadingProgress();
            }).catch(error => {
                console.error('Error processing nodes:', error);
                hideLoadingProgress();
            });

            // Dotlinks already configured in option.links before chart initialization

            // Chart events
            chartInstance.on('click', function(sender, args) {
                console.log('Node clicked:', args);
            });

            chartInstance.on('add', function(sender, node) {
                console.log('Node added:', node);
            });

            chartInstance.on('update', function(sender, oldNode, newNode) {
                console.log('Node updated:', oldNode, newNode);
            });

            chartInstance.on('remove', function(sender, nodeId) {
                console.log('Node removed:', nodeId);
            });

            // Make chart accessible globally
            window.orgChart = chartInstance;

            console.log('OrgChart initialized successfully');
        }

        // Initialize chart on load
        initializeChart();

        // Listen for Livewire component updates
        $wire.on('refreshChart', () => {
            console.log('Chart refresh event received, reinitializing...');
            setTimeout(() => {
                initializeChart();
            }, 200);
        });
    })();
</script>
@endscript
