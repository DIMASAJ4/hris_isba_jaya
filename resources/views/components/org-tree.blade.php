<style>
/* CSS Org Chart Tree */
.tree {
    display: flex;
    justify-content: center;
    padding-top: 20px;
}
.tree ul {
    padding-top: 20px; position: relative;
    transition: all 0.5s;
    display: flex;
    justify-content: center;
}
.tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
    transition: all 0.5s;
}

/* Garis penghubung */
.tree li::before, .tree li::after {
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 2px solid #980D0D;
    width: 50%; height: 20px;
}
.tree li::after {
    right: auto; left: 50%;
    border-left: 2px solid #980D0D;
}

/* Menghilangkan garis pada node pertama dan terakhir */
.tree li:only-child::after, .tree li:only-child::before {
    display: none;
}
.tree li:only-child {
    padding-top: 0;
}
.tree li:first-child::before, .tree li:last-child::after {
    border: 0 none;
}
/* Menambahkan garis vertikal ke node pertama/terakhir */
.tree li:first-child::after {
    border-radius: 5px 0 0 0;
}
.tree li:last-child::before {
    border-right: 2px solid #980D0D;
    border-radius: 0 5px 0 0;
}

/* Garis vertikal ke bawah dari parent */
.tree ul ul::before {
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #980D0D;
    width: 0; height: 20px;
    margin-left: -1px;
}

/* Styling Card Node */
.node-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 10px;
    display: inline-block;
    min-width: 150px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s;
}
.node-card:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-color: #980D0D;
}
.node-img {
    width: 50px; height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 5px auto;
    border: 2px solid #f3f4f6;
}
.node-name {
    font-size: 12px;
    font-weight: bold;
    color: #1E3A5F;
    margin: 0;
}
.node-pos {
    font-size: 10px;
    color: #6b7280;
    margin: 0;
}
.dept-title {
    background: #1E3A5F;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 4px 10px;
    border-radius: 20px;
    margin-bottom: 10px;
    display: inline-block;
}
.members-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
</style>

@php
function renderNode($position) {
    if (!$position) return;
    
    echo '<div class="node-card" style="box-shadow: 0 4px 6px rgba(152, 13, 13, 0.05); border-top: 3px solid #980D0D;">';
    echo '<p class="node-pos" style="border-bottom: 1px solid #f3f4f6; padding-bottom: 6px; margin-bottom: 10px; font-weight: 900; color: #1E3A5F; text-transform: uppercase;">'.$position->name.'</p>';
    
    if ($position->members->isEmpty()) {
        echo '<div style="display:flex; flex-direction:column; align-items:center; margin-bottom: 5px;">';
        echo '<div class="node-img" style="background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#9ca3af; border:none;">?</div>';
        echo '<p class="node-name" style="color:#9ca3af; font-style:italic; font-weight:normal;">Kosong</p>';
        echo '</div>';
    } else {
        foreach ($position->members as $member) {
            $name = $member->full_name;
            $photo = $member->photo ? asset('storage/' . $member->photo) : null;
            
            echo '<div style="display:flex; flex-direction:column; align-items:center; margin-bottom: 8px; padding: 6px; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background=\'#fff1f2\'" onmouseout="this.style.background=\'transparent\'">';
            if ($photo) {
                echo '<img src="'.$photo.'" class="node-img" style="border-color: #fecdd3; padding: 2px;">';
            } else {
                echo '<div class="node-img" style="background:#ffe4e6; border-color: #fecdd3; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#be123c; font-size: 16px;">'.substr($name, 0, 1).'</div>';
            }
            echo '<p class="node-name" style="color: #333;">'.$name.'</p>';
            echo '<p style="font-size: 9px; color: #9ca3af; margin: 0;">'.$member->university.'</p>';
            echo '</div>';
        }
    }
    echo '</div>';
}
@endphp

<div class="w-full overflow-x-auto pb-10">
    <div class="tree">
        <ul>
            <li>
                {{ renderNode($ketua) }}
                @if($wakil)
                <ul>
                    <li>
                        {{ renderNode($wakil) }}
                        <ul>
                            <li>
                                {{ renderNode($sekretaris) }}
                            </li>
                            <li>
                                {{ renderNode($bendahara) }}
                            </li>
                            @foreach($otherDepartments as $dept)
                            <li>
                                <div class="node-card" style="background: #f8fafc; border-style: dashed;">
                                    <div class="dept-title" style="background-color: {{ $dept->color ?? '#1E3A5F' }}">{{ $dept->name }}</div>
                                    <div class="members-list">
                                        @foreach($dept->positions as $pos)
                                            {{ renderNode($pos) }}
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                @endif
            </li>
        </ul>
    </div>
</div>
