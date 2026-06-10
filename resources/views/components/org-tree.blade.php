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
    border-top: 2px solid #ccc;
    width: 50%; height: 20px;
}
.tree li::after {
    right: auto; left: 50%;
    border-left: 2px solid #ccc;
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
    border-right: 2px solid #ccc;
    border-radius: 0 5px 0 0;
}

/* Garis vertikal ke bawah dari parent */
.tree ul ul::before {
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #ccc;
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
    $member = $position->members->first();
    $name = $member ? $member->full_name : 'Kosong';
    $photo = $member && $member->photo ? asset('storage/' . $member->photo) : asset('images/default-avatar.png'); // Fallback to a generic or empty
    
    echo '<div class="node-card">';
    if ($member && $member->photo) {
        echo '<img src="'.$photo.'" class="node-img">';
    } else {
        echo '<div class="node-img" style="background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#9ca3af;">'.substr($name, 0, 1).'</div>';
    }
    echo '<p class="node-name">'.$name.'</p>';
    echo '<p class="node-pos">'.$position->name.'</p>';
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
