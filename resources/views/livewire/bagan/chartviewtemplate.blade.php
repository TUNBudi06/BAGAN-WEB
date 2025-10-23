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

            // Function to convert image to base64
            function imageToBase64(url) {
                return new Promise((resolve, reject) => {
                    fetch(url)
                        .then(res => res.blob())
                        .then(blob => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.onerror = reject;
                            reader.readAsDataURL(blob);
                        })
                        .catch(reject);
                });
            }

            // Process nodes and convert images to base64
            async function processNodes() {
                const processedNodes = [];

                for (const node of nodesData) {
                    let temp = {
                        'id': node.chart_id,
                        'tags': [],
                    }
                    if (node.chart_pid) {
                        temp.pid = node.chart_pid;
                    }
                    if (node.chart_stpid) {
                        temp.stpid = node.chart_stpid;
                    }
                    if (node.chart_ppid) {
                        temp.ppid = node.chart_ppid;
                    }

                    if (node.name) {
                        temp.name = node.name;
                    }

                    if (node.get_template_bagan && node.get_template_bagan.template) {
                        temp.tags.push(node.get_template_bagan.name);
                    }

                    if (node.get_node_type && node.get_node_type.name) {
                        temp.tags.push(node.get_node_type.template);
                    }

                    if (node.get_sub_level && node.get_sub_level.name) {
                        temp.tags.push(node.get_sub_level.name);
                    }

                    // Add other node properties
                    if (node.label) temp.label = node.label;
                    if (node.nik) temp.nik = node.nik;
                    if (node.team) temp.team = node.team;
                    if (node.phone) temp.phone = node.phone;

                    // Convert image to base64 for PDF export
                    if(node.user) {
                        if (node.user.image_path) {
                            const imageUrl = "{{ asset('storage') }}/" + node.user.image_path;
                            try {
                                temp.img = await imageToBase64(imageUrl);
                            } catch (error) {
                                console.warn('Failed to load image:', imageUrl, error);
                                // Fallback to original URL if conversion fails
                                temp.img = imageUrl;
                            }
                        }
                    }

                    processedNodes.push(temp);
                }

                return processedNodes;
            }

            // Load chart with processed nodes
            processNodes().then(processedNodes => {
                nodes = processedNodes;
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
